<?php
session_start();

require_once '../../../database/index.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $fullName = htmlspecialchars($_POST['fullName']);
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirm_password'];
  $accountType = htmlspecialchars($_POST['account_type']);

  // Validação dos dados
  if (empty($fullName) || empty($email) || empty($password) || empty($accountType)) {
    $_SESSION['error_message'] = "Todos os campos são obrigatórios.";
    header("Location: ../../auth/register/index.php");
    exit();
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error_message'] = "O formato do email é inválido.";
    header("Location: ../../auth/register/index.php");
    exit();
  }
  if ($password !== $confirmPassword) {
    $_SESSION['error_message'] = "As senhas não coincidem.";
    header("Location: ../../auth/register/index.php");
    exit();
  }
  if (strlen($password) < 6) {
    $_SESSION['error_message'] = "A senha deve ter no mínimo 6 caracteres.";
    header("Location: ../../auth/register/index.php");
    exit();
  }

  // Verifica se o email já existe no banco
  $sql_check = "SELECT id FROM usuarios WHERE email = ?";
  $stmt_check = mysqli_prepare($conexao, $sql_check);
  mysqli_stmt_bind_param($stmt_check, "s", $email);
  mysqli_stmt_execute($stmt_check);
  mysqli_stmt_store_result($stmt_check);

  if (mysqli_stmt_num_rows($stmt_check) > 0) {
    $_SESSION['error_message'] = "Este email já está cadastrado. Tente fazer o login.";
    header("Location: ../../auth/register/index.php");
    exit();
  }
  mysqli_stmt_close($stmt_check);

  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Insere o novo usuário no banco de dados
  $sql_insert = "INSERT INTO usuarios (nome, email, senha_hash, tipo_conta) VALUES (?, ?, ?, ?)";
  $stmt_insert = mysqli_prepare($conexao, $sql_insert);
  mysqli_stmt_bind_param($stmt_insert, "ssss", $fullName, $email, $hashedPassword, $accountType);

  if (mysqli_stmt_execute($stmt_insert)) {
    $_SESSION['success_message'] = "Registro realizado com sucesso! Por favor, faça o login.";
    mysqli_stmt_close($stmt_insert);
    header("Location: ../../auth/login/index.php");
    exit();
  } else {
    $_SESSION['error_message'] = "Ocorreu um erro inesperado ao criar a conta. Tente novamente.";
    mysqli_stmt_close($stmt_insert);
    header("Location: ../../auth/register/index.php");
    exit();
  }

} else {
  // Se o script for acessado diretamente
  header("Location: ../../auth/register/index.php");
  exit();
}
