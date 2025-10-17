<?php
require_once '../../includes/auth_check.php';
require_once '../../../database/index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  mysqli_begin_transaction($conexao);

  try {
    $cliente_id = !empty($_POST['cliente_id']) ? $_POST['cliente_id'] : null;
    $metodo_pagamento = $_POST['metodo_pagamento'];
    $status_pagamento = $_POST['status_pagamento'];
    $produtos = $_POST['produtos'] ?? [];
    $produtos_livres = $_POST['produtos_livres'] ?? [];
    $precos_livres = $_POST['precos_livres'] ?? [];
    $quantidades = $_POST['quantidades'] ?? [];
    $valor_total = 0;

    // Calcula o valor total no servidor
    for ($i = 0; $i < count($produtos); $i++) {
      $produto_id = $produtos[$i];
      $quantidade = floatval($quantidades[$i]); // Converte para número
      
      if ($produto_id === 'livre') {
        // Produto livre - usa preço informado
        $preco_unitario = floatval($precos_livres[$i]); // Converte para número
        $valor_total += $preco_unitario * $quantidade;
      } else if (!empty($produto_id)) {
        // Produto cadastrado
        $produtoResult = mysqli_query($conexao, "SELECT valor_venda FROM produtos WHERE id = $produto_id");
        $produto = mysqli_fetch_assoc($produtoResult);
        $preco_unitario = floatval($produto['valor_venda']); // Converte para número
        $valor_total += $preco_unitario * $quantidade;
      }
    }

    // 1. Insere na tabela 'vendas'
    $sqlVenda = "INSERT INTO vendas (cliente_id, valor_total, metodo_pagamento, status_pagamento) VALUES (?, ?, ?, ?)";
    $stmtVenda = mysqli_prepare($conexao, $sqlVenda);
    mysqli_stmt_bind_param($stmtVenda, "idss", $cliente_id, $valor_total, $metodo_pagamento, $status_pagamento);
    mysqli_stmt_execute($stmtVenda);
    $venda_id = mysqli_insert_id($conexao);

    // 2. Insere na tabela 'itens_venda' e 'itens_venda_livres'
    for ($i = 0; $i < count($produtos); $i++) {
      $produto_id = $produtos[$i];
      $quantidade = intval($quantidades[$i]); // Converte para inteiro
      
      if ($produto_id === 'livre') {
        // Produto livre - insere na tabela de itens livres
        $nome_produto = trim($produtos_livres[$i]);
        $preco_unitario = floatval($precos_livres[$i]); // Converte para número
        
        $sqlItemLivre = "INSERT INTO itens_venda_livres (venda_id, nome_produto, quantidade, preco_unitario) VALUES (?, ?, ?, ?)";
        $stmtItemLivre = mysqli_prepare($conexao, $sqlItemLivre);
        mysqli_stmt_bind_param($stmtItemLivre, "isid", $venda_id, $nome_produto, $quantidade, $preco_unitario);
        mysqli_stmt_execute($stmtItemLivre);
      } else if (!empty($produto_id)) {
        // Produto cadastrado - insere na tabela normal e atualiza estoque
        $produtoResult = mysqli_query($conexao, "SELECT valor_venda FROM produtos WHERE id = $produto_id");
        $produto = mysqli_fetch_assoc($produtoResult);
        $preco_unitario = floatval($produto['valor_venda']); // Converte para número

        $sqlItem = "INSERT INTO itens_venda (venda_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)";
        $stmtItem = mysqli_prepare($conexao, $sqlItem);
        mysqli_stmt_bind_param($stmtItem, "iiid", $venda_id, $produto_id, $quantidade, $preco_unitario);
        mysqli_stmt_execute($stmtItem);

        // Atualiza estoque apenas se o produto tem controle de estoque
        $sqlEstoque = "UPDATE produtos SET quantidade = quantidade - ? WHERE id = ? AND quantidade > 0";
        $stmtEstoque = mysqli_prepare($conexao, $sqlEstoque);
        mysqli_stmt_bind_param($stmtEstoque, "ii", $quantidade, $produto_id);
        mysqli_stmt_execute($stmtEstoque);
      }
    }

    mysqli_commit($conexao); // Confirma a transação
    $_SESSION['success_message'] = "Venda registrada com sucesso!";
  } catch (Exception $e) {
    mysqli_rollback($conexao); // Desfaz tudo em caso de erro
    $_SESSION['error_message'] = "Erro ao registrar venda: " . $e->getMessage();
  }

  header('Location: ../../pages/dashboard/vendas.php');
  exit();
}
