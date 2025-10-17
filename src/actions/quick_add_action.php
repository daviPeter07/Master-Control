<?php
require_once '../includes/auth_check.php';
require_once '../../database/index.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    // Função para limpar dados formatados
    function cleanFormattedData($data) {
      return preg_replace('/[^\d]/', '', $data);
    }

    function cleanCurrencyValue($value) {
      $value = str_replace(['R$', ' ', '.'], '', $value);
      $value = str_replace(',', '.', $value);
      return floatval($value);
    }

    function cleanNumberValue($value) {
      return intval(str_replace('.', '', $value));
    }

    $action = $_POST['action'] ?? '';
    
    if ($action === 'quick_add_cliente') {
      // Cadastro rápido de cliente
      $nome = trim($_POST['nome']);
      $tipo_cliente = $_POST['tipo_cliente'];
      $telefone = trim($_POST['telefone']) ? cleanFormattedData($_POST['telefone']) : null;
      
      if (empty($nome) || empty($tipo_cliente)) {
        throw new Exception("Nome e Tipo de Cliente são obrigatórios.");
      }
      
      if (!in_array($tipo_cliente, ['CONSUMIDOR', 'REVENDEDORA'])) {
        throw new Exception("Tipo de cliente inválido.");
      }
      
      $sql = "INSERT INTO clientes (nome, tipo_cliente, telefone) VALUES (?, ?, ?)";
      $stmt = mysqli_prepare($conexao, $sql);
      mysqli_stmt_bind_param($stmt, "sss", $nome, $tipo_cliente, $telefone);
      
      if (mysqli_stmt_execute($stmt)) {
        $cliente_id = mysqli_insert_id($conexao);
        echo json_encode([
          'success' => true,
          'message' => 'Cliente cadastrado com sucesso!',
          'cliente' => [
            'id' => $cliente_id,
            'nome' => $nome,
            'tipo_cliente' => $tipo_cliente,
            'telefone' => $telefone
          ]
        ]);
      } else {
        throw new Exception("Erro ao cadastrar cliente: " . mysqli_stmt_error($stmt));
      }
      
    } elseif ($action === 'quick_add_produto') {
      // Cadastro rápido de produto
      $nome = trim($_POST['nome']);
      $descricao = trim($_POST['descricao']) ?: '';
      $valor_venda = cleanCurrencyValue($_POST['valor_venda']);
      $quantidade = cleanNumberValue($_POST['quantidade']);
      
      if (empty($nome) || $valor_venda <= 0) {
        throw new Exception("Nome e Valor de Venda são obrigatórios.");
      }
      
      $sql = "INSERT INTO produtos (nome, descricao, valor_custo, valor_venda, quantidade, genero) VALUES (?, ?, 0, ?, ?, 'UNISSEX')";
      $stmt = mysqli_prepare($conexao, $sql);
      mysqli_stmt_bind_param($stmt, "ssdi", $nome, $descricao, $valor_venda, $quantidade);
      
      if (mysqli_stmt_execute($stmt)) {
        $produto_id = mysqli_insert_id($conexao);
        echo json_encode([
          'success' => true,
          'message' => 'Produto cadastrado com sucesso!',
          'produto' => [
            'id' => $produto_id,
            'nome' => $nome,
            'descricao' => $descricao,
            'valor_venda' => $valor_venda,
            'quantidade' => $quantidade
          ]
        ]);
      } else {
        throw new Exception("Erro ao cadastrar produto: " . mysqli_stmt_error($stmt));
      }
      
    } else {
      throw new Exception("Ação inválida.");
    }
    
  } catch (Exception $e) {
    echo json_encode([
      'success' => false,
      'message' => $e->getMessage()
    ]);
  }
} else {
  echo json_encode([
    'success' => false,
    'message' => 'Método não permitido.'
  ]);
}

mysqli_close($conexao);
?>
