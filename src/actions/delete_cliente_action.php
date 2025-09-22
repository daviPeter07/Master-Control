<?php
session_start();
require_once '../includes/auth_check.php';
require_once '../../database/index.php';

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  $sql = "DELETE FROM clientes WHERE id = ?";
  $stmt = mysqli_prepare($conexao, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (mysqli_stmt_execute($stmt)) {
      $_SESSION['success_message'] = "Cliente excluído com sucesso!";
    } else {
      $_SESSION['error_message'] = "Erro ao excluir cliente. Verifique se ele não está associado a vendas.";
    }
    mysqli_stmt_close($stmt);
  }

  mysqli_close($conexao);
  header('Location: /masterControl/src/pages/dashboard/clientes.php');
  exit();
}
