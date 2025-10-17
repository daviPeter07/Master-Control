<?php
session_start();
require_once '../../includes/auth_check.php';
require_once '../../../database/index.php';

// Verifica se o método da requisição é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Função para limpar dados formatados
  function cleanFormattedData($data) {
    // Remove formatação de telefone
    $data = preg_replace('/[^\d]/', '', $data);
    return $data;
  }

  // Coleta e sanitiza os dados do formulário
  $nome = trim($_POST['nome']);
  $tipo_cliente = $_POST['tipo_cliente'];
  $telefone = trim($_POST['telefone']) ? cleanFormattedData($_POST['telefone']) : null; // Define como null se estiver vazio

  // Validação
  if (empty($nome) || empty($tipo_cliente)) {
    $_SESSION['error_message'] = "Nome e Tipo de Cliente são obrigatórios.";
    header('Location: /masterControl/src/pages/dashboard/clientes.php');
    exit();
  }

  if (!in_array($tipo_cliente, ['CONSUMIDOR', 'REVENDEDORA'])) {
    $_SESSION['error_message'] = "Tipo de cliente inválido.";
    header('Location: /masterControl/src/pages/dashboard/clientes.php');
    exit();
  }

  // Prepara a query de inserção para evitar SQL Injection
  $sql = "INSERT INTO clientes (nome, tipo_cliente, telefone) VALUES (?, ?, ?)";

  $stmt = mysqli_prepare($conexao, $sql);

  if ($stmt) {
    // "sss" significa que estamos passando três strings (string, string, string)
    mysqli_stmt_bind_param($stmt, "sss", $nome, $tipo_cliente, $telefone);

    // Executa e verifica o resultado
    if (mysqli_stmt_execute($stmt)) {
      $_SESSION['success_message'] = "Cliente adicionado com sucesso!";
    } else {
      $_SESSION['error_message'] = "Erro ao adicionar cliente: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
  } else {
    $_SESSION['error_message'] = "Erro na preparação da consulta: " . mysqli_error($conexao);
  }

  mysqli_close($conexao);

  // Redireciona de volta para a página de clientes
  header('Location: /masterControl/src/pages/dashboard/clientes.php');
  exit();
} else {
  // Se não for POST, redireciona para a página inicial ou exibe erro
  $_SESSION['error_message'] = "Método de requisição inválido.";
  header('Location: /masterControl/src/pages/dashboard/clientes.php');
  exit();
}
