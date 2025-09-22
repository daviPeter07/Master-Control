<?php
require_once '../../includes/auth_check.php';
$currentPage = 'clientes';

// mockdata de clientes
$clientes = [
  ['id' => 1, 'nome' => 'João Silva', 'tipo_cliente' => 'CONSUMIDOR', 'telefone' => '(92) 99999-1111', 'criado_em' => '2025-09-01'],
  ['id' => 2, 'nome' => 'Maria Souza', 'tipo_cliente' => 'REVENDEDORA', 'telefone' => '(92) 98888-2222', 'criado_em' => '2025-09-05'],
  ['id' => 3, 'nome' => 'Carlos Lima', 'tipo_cliente' => 'CONSUMIDOR', 'telefone' => '(92) 97777-3333', 'criado_em' => '2025-09-10'],
  ['id' => 4, 'nome' => 'Ana Pereira', 'tipo_cliente' => 'REVENDEDORA', 'telefone' => '(92) 96666-4444', 'criado_em' => '2025-09-12'],
  ['id' => 5, 'nome' => 'Pedro Alves', 'tipo_cliente' => 'CONSUMIDOR', 'telefone' => '(92) 95555-5555', 'criado_em' => '2025-09-15'],
  ['id' => 6, 'nome' => 'Beatriz Santos', 'tipo_cliente' => 'CONSUMIDOR', 'telefone' => '(92) 94444-6666', 'criado_em' => '2025-09-18'],
  ['id' => 7, 'nome' => 'Ricardo Mendes', 'tipo_cliente' => 'REVENDEDORA', 'telefone' => '(92) 93333-7777', 'criado_em' => '2025-09-20'],
];

$itensPorPagina = 5;
$totalClientes = count($clientes);
$totalPaginas = ceil($totalClientes / $itensPorPagina);
$paginaAtual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$inicio = ($paginaAtual - 1) * $itensPorPagina;
$clientesPagina = array_slice($clientes, $inicio, $itensPorPagina);
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
          <button class="bg-[var(--color-primary)] text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:opacity-90 transition-opacity flex items-center gap-2">
            <svg class="w-5 h-5" xmlns="http://www.w.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Adicionar Cliente
          </button>
        </div>

        <div class="mb-6">
          <input type="search" placeholder="Pesquisar por cliente..." class="search-input w-full sm:max-w-sm p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-surface)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>

        <div class="bg-[var(--color-surface)] p-4 sm:p-6 rounded-lg shadow-md">

          <!-- Cards mobile -->
          <div class="grid gap-4 sm:hidden searchable-container">
            <?php foreach ($clientesPagina as $cliente): ?>
              <div class="p-4 border border-[var(--color-border)] rounded-lg searchable-item">
                <h2 class="font-semibold text-[var(--color-text-primary)]"><?= htmlspecialchars($cliente['nome']) ?></h2>
                <p class="text-sm text-[var(--color-text-secondary)]">Tipo: <?= htmlspecialchars($cliente['tipo_cliente']) ?></p>
                <p class="text-sm text-[var(--color-text-secondary)]">Telefone: <?= htmlspecialchars($cliente['telefone']) ?></p>
                <p class="text-sm text-[var(--color-text-secondary)]">Cliente desde: <?= date('d/m/Y', strtotime($cliente['criado_em'])) ?></p>
                <div class="flex gap-2 mt-2">
                  <a href="#" class="bg-blue-500 text-white px-3 py-1 text-sm rounded-lg flex items-center gap-1 hover:bg-blue-600">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M4 20h4l12-12-4-4-12 12v4z" />
                    </svg>
                    Editar
                  </a>
                  <a href="#" class="bg-red-500 text-white px-3 py-1 text-sm rounded-lg flex items-center gap-1 hover:bg-red-600">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Deletar
                  </a>
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
                      <a href="#" class="bg-blue-500 text-white px-3 py-1 rounded-lg flex items-center gap-1 hover:bg-blue-600 text-xs">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M4 20h4l12-12-4-4-12 12v4z" />
                        </svg>
                        Editar
                      </a>
                      <a href="#" class="bg-red-500 text-white px-3 py-1 rounded-lg flex items-center gap-1 hover:bg-red-600 text-xs">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Deletar
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <!-- ✅ PAGINAÇÃO APLICADA -->
          <div class="mt-4 flex justify-between items-center">
            <a href="?pagina=<?= max(1, $paginaAtual - 1) ?>" class="pag-prev <?= $paginaAtual <= 1 ? 'disabled' : '' ?>">Anterior</a>
            <span class="text-sm text-[var(--color-text-secondary)]">Página <?= $paginaAtual ?> de <?= $totalPaginas ?></span>
            <a href="?pagina=<?= min($totalPaginas, $paginaAtual + 1) ?>" class="pag-next <?= $paginaAtual >= $totalPaginas ? 'disabled' : '' ?>">Próximo</a>
          </div>
        </div>
      </main>
    </div>
  </div>
  <script src="../../scripts/dashboard.js"></script>
</body>

</html>