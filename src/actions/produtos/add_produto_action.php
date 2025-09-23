<?php
session_start();
require_once '../../includes/auth_check.php';
require_once '../../../database/index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Coleta e valida os dados
  $nome = trim($_POST['nome']);
  $descricao = trim($_POST['descricao']);
  $valor_custo = $_POST['valor_custo'];
  $valor_venda = $_POST['valor_venda'];
  $quantidade = $_POST['quantidade'];
  $genero = $_POST['genero'];
  $marca_id = !empty($_POST['marca_id']) ? $_POST['marca_id'] : null;
  $categoria_id = !empty($_POST['categoria_id']) ? $_POST['categoria_id'] : null;

  if (empty($nome) || !is_numeric($valor_venda) || !is_numeric($quantidade)) {
    $_SESSION['error_message'] = "Campos obrigatórios inválidos.";
    header('Location: /masterControl/src/pages/dashboard/produtos.php');
    exit();
  }

  $sql = "INSERT INTO produtos (nome, descricao, valor_custo, valor_venda, quantidade, genero, marca_id, categoria_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = mysqli_prepare($conexao, $sql);
  // s = string, d = double (decimal), i = integer
  mysqli_stmt_bind_param($stmt, "ssddisii", $nome, $descricao, $valor_custo, $valor_venda, $quantidade, $genero, $marca_id, $categoria_id);

  if (mysqli_stmt_execute($stmt)) {
    $_SESSION['success_message'] = "Produto adicionado com sucesso!";
  } else {
    $_SESSION['error_message'] = "Erro ao adicionar produto.";
  }

  mysqli_stmt_close($stmt);
  mysqli_close($conexao);
  header('Location: /masterControl/src/pages/dashboard/produtos.php');
  exit();
}
