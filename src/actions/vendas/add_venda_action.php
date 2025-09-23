<?php
session_start();
require_once '../includes/auth_check.php';
require_once '../../database/index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  mysqli_begin_transaction($conexao);

  try {
    $cliente_id = $_POST['cliente_id'];
    $metodo_pagamento = $_POST['metodo_pagamento'];
    $status_pagamento = $_POST['status_pagamento'];
    $produtos = $_POST['produtos'];
    $quantidades = $_POST['quantidades'];
    $valor_total = 0;

    // Calcula o valor total no servidor
    for ($i = 0; $i < count($produtos); $i++) {
      $produto_id = $produtos[$i];
      $quantidade = $quantidades[$i];
      $produtoResult = mysqli_query($conexao, "SELECT valor_venda FROM produtos WHERE id = $produto_id");
      $produto = mysqli_fetch_assoc($produtoResult);
      $valor_total += $produto['valor_venda'] * $quantidade;
    }

    // 1. Insere na tabela 'vendas'
    $sqlVenda = "INSERT INTO vendas (cliente_id, valor_total, metodo_pagamento, status_pagamento) VALUES (?, ?, ?, ?)";
    $stmtVenda = mysqli_prepare($conexao, $sqlVenda);
    mysqli_stmt_bind_param($stmtVenda, "idss", $cliente_id, $valor_total, $metodo_pagamento, $status_pagamento);
    mysqli_stmt_execute($stmtVenda);
    $venda_id = mysqli_insert_id($conexao);

    // 2. Insere na tabela 'itens_venda' e atualiza o estoque
    for ($i = 0; $i < count($produtos); $i++) {
      $produto_id = $produtos[$i];
      $quantidade = $quantidades[$i];
      $produtoResult = mysqli_query($conexao, "SELECT valor_venda FROM produtos WHERE id = $produto_id");
      $produto = mysqli_fetch_assoc($produtoResult);
      $preco_unitario = $produto['valor_venda'];

      $sqlItem = "INSERT INTO itens_venda (venda_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)";
      $stmtItem = mysqli_prepare($conexao, $sqlItem);
      mysqli_stmt_bind_param($stmtItem, "iiid", $venda_id, $produto_id, $quantidade, $preco_unitario);
      mysqli_stmt_execute($stmtItem);

      $sqlEstoque = "UPDATE produtos SET quantidade = quantidade - ? WHERE id = ?";
      $stmtEstoque = mysqli_prepare($conexao, $sqlEstoque);
      mysqli_stmt_bind_param($stmtEstoque, "ii", $quantidade, $produto_id);
      mysqli_stmt_execute($stmtEstoque);
    }

    mysqli_commit($conexao); // Confirma a transação
    $_SESSION['success_message'] = "Venda registrada com sucesso!";
  } catch (Exception $e) {
    mysqli_rollback($conexao); // Desfaz tudo em caso de erro
    $_SESSION['error_message'] = "Erro ao registrar venda: " . $e->getMessage();
  }

  header('Location: /masterControl/src/pages/dashboard/vendas.php');
  exit();
}
