<?php

$config = require_once('env.php');

$servidor = $config['db_server'];
$usuario = $config['db_user'];
$senha = $config['db_password'];
$banco = $config['db_name'];

$conexao = mysqli_connect($servidor, $usuario, $senha, $banco);

if (!$conexao) {
  die("Falha na conexão: " . mysqli_connect_error());
}

mysqli_set_charset($conexao, "utf8mb4");