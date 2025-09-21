<?php
session_start();

require_once '../../database/index.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $password = $_POST['password'];

  if (empty($email) || empty($password)) {
    $_SESSION['error_message'] = "Email e senha são obrigatórios.";
    header("Location: ../auth/login/");
    exit();
  }

  $sql = "SELECT id, nome, senha_hash FROM usuarios WHERE email = ?";
  $stmt = mysqli_prepare($conexao, $sql);

  mysqli_stmt_bind_param($stmt, "s", $email);
  mysqli_stmt_execute($stmt);

  $result = mysqli_stmt_get_result($stmt);
  $user = mysqli_fetch_assoc($result);

  if ($user && password_verify($password, $user['senha_hash'])) {

    session_regenerate_id(true);

    $_SESSION['logged_in'] = true;
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['nome'];

    header("Location: ../pages/dashboard/");
    exit();
  } else {

    $_SESSION['error_message'] = "Email ou senha inválidos.";

    header("Location: ../auth/login/");
    exit();
  }
} else {
  header("Location: ../auth/login/");
  exit();
}
