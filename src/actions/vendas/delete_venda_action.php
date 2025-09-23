<?php
session_start();
require_once '../../includes/auth_check.php';
require_once '../../../database/index.php';

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  mysqli_begin_transaction($conexao);
  try {
    // Pega os itens da venda para devolver ao estoque
    $itensSql = "SELECT produto_id, quantidade FROM itens_venda WHERE venda_id = ?";
    $stmtItens = mysqli_prepare($conexao, $itensSql);
    mysqli_stmt_bind_param($stmtItens, "i", $id);
    mysqli_stmt_execute($stmtItens);
    $resultItens = mysqli_stmt_get_result($stmtItens);

    while ($item = mysqli_fetch_assoc($resultItens)) {
      $updateEstoqueSql = "UPDATE produtos SET quantidade = quantidade + ? WHERE id = ?";
      $stmtEstoque = mysqli_prepare($conexao, $updateEstoqueSql);
      mysqli_stmt_bind_param($stmtEstoque, "ii", $item['quantidade'], $item['produto_id']);
      mysqli_stmt_execute($stmtEstoque);
    }

    // Deleta a venda
    $sql = "DELETE FROM vendas WHERE id = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    mysqli_commit($conexao);
    $_SESSION['success_message'] = "Venda excluída e estoque restaurado!";
  } catch (Exception $e) {
    mysqli_rollback($conexao);
    $_SESSION['error_message'] = "Erro ao excluir venda.";
  }

  header('Location: /masterControl/src/pages/dashboard/vendas.php');
  exit();
}
