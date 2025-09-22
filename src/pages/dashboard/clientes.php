<?php
require_once '../../includes/auth_check.php';
require_once '../../../database/index.php';
$currentPage = 'clientes';

//Lógica de Paginação com o Banco de Dados 

// Definir quantos itens por página
$itensPorPagina = 5;

// Contar o total de clientes no banco
$totalClientesSql = "SELECT COUNT(id) AS total FROM clientes";
$totalResult = mysqli_query($conexao, $totalClientesSql);
$totalClientes = mysqli_fetch_assoc($totalResult)['total'];
$totalPaginas = $totalClientes > 0 ? ceil($totalClientes / $itensPorPagina) : 1;

// Pegar a página atual da URL e validar
$paginaAtual = isset($_GET['pagina']) ? max(1, min((int)$_GET['pagina'], $totalPaginas)) : 1;

// Calcular o offset para a query SQL
$inicio = ($paginaAtual - 1) * $itensPorPagina;

// Query para buscar os clientes da página atual usando prepared statements
$clientesPaginaSql = "SELECT id, nome, tipo_cliente, telefone, criado_em FROM clientes ORDER BY nome ASC LIMIT ?, ?";

// Prepara, executa e busca os resultados
$stmt = mysqli_prepare($conexao, $clientesPaginaSql);
mysqli_stmt_bind_param($stmt, "ii", $inicio, $itensPorPagina);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Monta o array com os clientes da página
$clientesPagina = [];
while ($row = mysqli_fetch_assoc($result)) {
  $clientesPagina[] = $row;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Clientes - Master Control</title>
  <script src="../../scripts/theme.js"></script>
  <link rel="stylesheet" href="../../styles/styles.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[var(--color-background)] group" data-current-page="clientes">

  <div class="flex">
    <?php require_once '../../includes/components/sidebar.php'; ?>
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 z-40 lg:hidden group-[.sidebar-closed]:hidden"></div>
    <div class="flex-1 flex flex-col min-h-screen">
      <?php require_once '../../includes/components/header.php'; ?>

      <main class="p-4 md:p-8 transition-all duration-300 lg:ml-64 group-[.sidebar-closed]:lg:ml-0">

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
          <h1 class="text-3xl font-bold text-[var(--color-text-primary)]">Gestão de Clientes</h1>
          <button id="open-modal-btn" class="bg-[var(--color-primary)] text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:opacity-90 transition-opacity flex items-center gap-2">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Adicionar Cliente
          </button>
        </div>

        <?php require_once '../../includes/components/modal_add_cliente.php' ?>

        <div class="mb-6">
          <input type="search" id="searchInput" placeholder="Pesquisar por cliente..." class="search-input w-full sm:max-w-sm p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-surface)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>

        <div class="bg-[var(--color-surface)] p-4 sm:p-6 rounded-lg shadow-md">
          <?php if (empty($clientesPagina)): ?>
            <div class="text-center py-8">
              <p class="text-[var(--color-text-secondary)]">Nenhum cliente encontrado.</p>
              <p class="text-[var(--color-text-secondary)] mt-2">Clique em "Adicionar Cliente" para começar.</p>
            </div>
          <?php else: ?>
            <!-- Cards mobile -->
            <div class="grid gap-4 sm:hidden searchable-container">
              <?php foreach ($clientesPagina as $cliente): ?>
                <div class="p-4 border border-[var(--color-border)] rounded-lg searchable-item">
                  <h2 class="font-semibold text-[var(--color-text-primary)]"><?= htmlspecialchars($cliente['nome']) ?></h2>
                  <p class="text-sm text-[var(--color-text-secondary)]">Tipo: <?= htmlspecialchars($cliente['tipo_cliente']) ?></p>
                  <p class="text-sm text-[var(--color-text-secondary)]">Telefone: <?= htmlspecialchars($cliente['telefone']) ?></p>
                  <p class="text-sm text-[var(--color-text-secondary)]">Cliente desde: <?= date('d/m/Y', strtotime($cliente['criado_em'])) ?></p>
                  <div class="flex gap-2 mt-2">
                    <button class="open-edit-modal-btn bg-blue-500 text-white px-3 py-1 rounded-lg flex items-center gap-1 hover:bg-blue-600 text-xs" data-id="<?= $cliente['id'] ?>" data-nome="<?= htmlspecialchars($cliente['nome']) ?>" data-tipo="<?= $cliente['tipo_cliente'] ?>" data-telefone="<?= htmlspecialchars($cliente['telefone']) ?>">Editar</button>
                    <button class="open-delete-modal-btn bg-red-500 text-white px-3 py-1 rounded-lg flex items-center gap-1 hover:bg-red-600 text-xs" data-id="<?= $cliente['id'] ?>" data-nome="<?= htmlspecialchars($cliente['nome']) ?>">Deletar</button>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- Tabela desktop -->
            <div class="overflow-x-auto hidden sm:block">
              <table class="w-full text-left text-sm sm:text-base min-w-[700px] searchable-table">
                <thead>
                  <tr class="border-b border-[var(--color-border)]">
                    <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Nome</th>
                    <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Tipo</th>
                    <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Telefone</th>
                    <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Cliente Desde</th>
                    <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($clientesPagina as $cliente): ?>
                    <tr class="border-b border-[var(--color-border)] hover:bg-[var(--color-background)] searchable-row">
                      <td class="p-3 font-medium text-[var(--color-text-primary)]"><?= htmlspecialchars($cliente['nome']) ?></td>
                      <td class="p-3 text-[var(--color-text-secondary)]"><?= htmlspecialchars($cliente['tipo_cliente']) ?></td>
                      <td class="p-3 text-[var(--color-text-secondary)]"><?= htmlspecialchars($cliente['telefone']) ?></td>
                      <td class="p-3 text-[var(--color-text-secondary)]"><?= date('d/m/Y', strtotime($cliente['criado_em'])) ?></td>
                      <td class="p-3 flex gap-2">
                        <button class="open-edit-modal-btn bg-blue-500 text-white px-3 py-1 rounded-lg flex items-center gap-1 hover:bg-blue-600 text-xs" data-id="<?= $cliente['id'] ?>" data-nome="<?= htmlspecialchars($cliente['nome']) ?>" data-tipo="<?= $cliente['tipo_cliente'] ?>" data-telefone="<?= htmlspecialchars($cliente['telefone']) ?>">Editar</button>
                        <button class="open-delete-modal-btn bg-red-500 text-white px-3 py-1 rounded-lg flex items-center gap-1 hover:bg-red-600 text-xs" data-id="<?= $cliente['id'] ?>" data-nome="<?= htmlspecialchars($cliente['nome']) ?>">Deletar</button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>

            <!-- Paginação -->
            <div class="mt-4 flex justify-between items-center">
              <a href="?pagina=<?= max(1, $paginaAtual - 1) ?>" class="pag-prev <?= $paginaAtual <= 1 ? 'disabled' : '' ?>">Anterior</a>
              <span class="text-sm text-[var(--color-text-secondary)]">Página <?= $paginaAtual ?> de <?= $totalPaginas ?></span>
              <a href="?pagina=<?= min($totalPaginas, $paginaAtual + 1) ?>" class="pag-next <?= $paginaAtual >= $totalPaginas ? 'disabled' : '' ?>">Próximo</a>
            </div>
          <?php endif; ?>
        </div>
      </main>
    </div>
  </div>

  <?php require_once '../../includes/components/modal_add_cliente.php'; ?>
  <?php require_once '../../includes/components/modal_edit_cliente.php'; ?>
  <?php require_once '../../includes/components/modal_delete_confirm.php'; ?>

  <script src="../../scripts/dashboard.js"></script>
</body>

</html>