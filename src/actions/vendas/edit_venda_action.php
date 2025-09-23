<?php
session_start();
require_once '../../includes/auth_check.php';
require_once '../../../database/index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  mysqli_begin_transaction($conexao); 

  try {
    // Dados principais da venda
    $venda_id = $_POST['id'];
    $cliente_id = $_POST['cliente_id'];
    $metodo_pagamento = $_POST['metodo_pagamento'];
    $status_pagamento = $_POST['status_pagamento'];

    // Novos itens da venda (podem não existir se todos foram removidos)
    $produtos_novos = $_POST['produtos'] ?? [];
    $quantidades_novas = $_POST['quantidades'] ?? [];
    $novo_valor_total = 0;


    // Buscar itens antigos para devolver ao estoque
    $sql_itens_antigos = "SELECT produto_id, quantidade FROM itens_venda WHERE venda_id = ?";
    $stmt_antigos = mysqli_prepare($conexao, $sql_itens_antigos);
    mysqli_stmt_bind_param($stmt_antigos, "i", $venda_id);
    mysqli_stmt_execute($stmt_antigos);
    $result_antigos = mysqli_stmt_get_result($stmt_antigos);
    while ($item = mysqli_fetch_assoc($result_antigos)) {
      $sql_devolve_estoque = "UPDATE produtos SET quantidade = quantidade + ? WHERE id = ?";
      $stmt_devolve = mysqli_prepare($conexao, $sql_devolve_estoque);
      mysqli_stmt_bind_param($stmt_devolve, "ii", $item['quantidade'], $item['produto_id']);
      mysqli_stmt_execute($stmt_devolve);
    }

    // Apagar todos os itens antigos da venda
    $sql_delete_itens = "DELETE FROM itens_venda WHERE venda_id = ?";
    $stmt_delete = mysqli_prepare($conexao, $sql_delete_itens);
    mysqli_stmt_bind_param($stmt_delete, "i", $venda_id);
    mysqli_stmt_execute($stmt_delete);

    // Calcular novo valor total e inserir novos itens
    for ($i = 0; $i < count($produtos_novos); $i++) {
      $produto_id = $produtos_novos[$i];
      $quantidade = $quantidades_novas[$i];

      // Busca o preço atual do produto
      $produtoResult = mysqli_query($conexao, "SELECT valor_venda FROM produtos WHERE id = $produto_id");
      $produto = mysqli_fetch_assoc($produtoResult);
      $preco_unitario = $produto['valor_venda'];
      $novo_valor_total += $preco_unitario * $quantidade;

      // Insere o novo item
      $sql_novo_item = "INSERT INTO itens_venda (venda_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)";
      $stmt_novo_item = mysqli_prepare($conexao, $sql_novo_item);
      mysqli_stmt_bind_param($stmt_novo_item, "iiid", $venda_id, $produto_id, $quantidade, $preco_unitario);
      mysqli_stmt_execute($stmt_novo_item);

      // Deduz do estoque
      $sql_tira_estoque = "UPDATE produtos SET quantidade = quantidade - ? WHERE id = ?";
      $stmt_tira = mysqli_prepare($conexao, $sql_tira_estoque);
      mysqli_stmt_bind_param($stmt_tira, "ii", $quantidade, $produto_id);
      mysqli_stmt_execute($stmt_tira);
    }

    // Atualiza a venda principal com o novo valor total e outros dados
    $sql_update_venda = "UPDATE vendas SET cliente_id = ?, valor_total = ?, metodo_pagamento = ?, status_pagamento = ? WHERE id = ?";
    $stmt_update = mysqli_prepare($conexao, $sql_update_venda);
    mysqli_stmt_bind_param($stmt_update, "idssi", $cliente_id, $novo_valor_total, $metodo_pagamento, $status_pagamento, $venda_id);
    mysqli_stmt_execute($stmt_update);

    mysqli_commit($conexao);
    $_SESSION['success_message'] = "Venda atualizada com sucesso!";
  } catch (Exception $e) {
    mysqli_rollback($conexao);
    $_SESSION['error_message'] = "Erro ao atualizar venda: " . $e->getMessage();
  }
  header('Location: /masterControl/src/pages/dashboard/vendas.php');
  exit();
}
