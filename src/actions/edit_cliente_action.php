<?php
session_start();
require_once '../includes/auth_check.php';
require_once '../../database/index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'];
  $nome = trim($_POST['nome']);
  $tipo_cliente = $_POST['tipo_cliente'];
  $telefone = trim($_POST['telefone']) ?: null;

  if (empty($id) || empty($nome) || empty($tipo_cliente)) {
    $_SESSION['error_message'] = "Todos os campos obrigatórios devem ser preenchidos.";
    header('Location: /masterControl/src/pages/dashboard/clientes.php');
    exit();
  }

  $sql = "UPDATE clientes SET nome = ?, tipo_cliente = ?, telefone = ? WHERE id = ?";
  $stmt = mysqli_prepare($conexao, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "sssi", $nome, $tipo_cliente, $telefone, $id);
    if (mysqli_stmt_execute($stmt)) {
      $_SESSION['success_message'] = "Cliente atualizado com sucesso!";
    } else {
      $_SESSION['error_message'] = "Erro ao atualizar cliente.";
    }
    mysqli_stmt_close($stmt);
  }

  mysqli_close($conexao);
  header('Location: /masterControl/src/pages/dashboard/clientes.php');
  exit();
}
