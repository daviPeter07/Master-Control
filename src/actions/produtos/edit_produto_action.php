<?php
session_start();
require_once '../../includes/auth_check.php';
require_once '../../../database/index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'];
  $nome = trim($_POST['nome']);
  $descricao = trim($_POST['descricao']);
  $valor_custo = $_POST['valor_custo'];
  $valor_venda = $_POST['valor_venda'];
  $quantidade = $_POST['quantidade'];
  $genero = $_POST['genero'];
  $marca_id = !empty($_POST['marca_id']) ? $_POST['marca_id'] : null;
  $categoria_id = !empty($_POST['categoria_id']) ? $_POST['categoria_id'] : null;

  if (empty($id) || empty($nome)) {
    // Validação básica
    $_SESSION['error_message'] = "Erro nos dados do produto.";
    header('Location: /masterControl/src/pages/dashboard/produtos.php');
    exit();
  }

  $sql = "UPDATE produtos SET nome=?, descricao=?, valor_custo=?, valor_venda=?, quantidade=?, genero=?, marca_id=?, categoria_id=? WHERE id=?";
  $stmt = mysqli_prepare($conexao, $sql);
  mysqli_stmt_bind_param($stmt, "ssddisiii", $nome, $descricao, $valor_custo, $valor_venda, $quantidade, $genero, $marca_id, $categoria_id, $id);

  if (mysqli_stmt_execute($stmt)) {
    $_SESSION['success_message'] = "Produto atualizado com sucesso!";
  } else {
    $_SESSION['error_message'] = "Erro ao atualizar produto.";
  }

  mysqli_stmt_close($stmt);
  mysqli_close($conexao);
  header('Location: /masterControl/src/pages/dashboard/produtos.php');
  exit();
}
