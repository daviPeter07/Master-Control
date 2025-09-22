<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {


  $_SESSION['error_message'] = "Você precisa fazer login para acessar esta página.";

  header("Location: /masterControl/src/auth/login/index.php");

  exit();
}
