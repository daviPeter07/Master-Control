<?php
session_start();
require_once '../../includes/auth_check.php';
require_once '../../../database/index.php';

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  $sql = "DELETE FROM produtos WHERE id = ?";
  $stmt = mysqli_prepare($conexao, $sql);
  mysqli_stmt_bind_param($stmt, "i", $id);

  if (mysqli_stmt_execute($stmt)) {
    $_SESSION['success_message'] = "Produto excluído com sucesso!";
  } else {
    $_SESSION['error_message'] = "Erro ao excluir produto. Verifique se ele não está associado a vendas.";
  }

  mysqli_stmt_close($stmt);
  mysqli_close($conexao);
  header('Location: /masterControl/src/pages/dashboard/produtos.php');
  exit();
}
