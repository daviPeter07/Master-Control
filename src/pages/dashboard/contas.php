<?php
require_once '../../includes/auth_check.php';
$currentPage = "contas";

// mock de dados para contas e clientes
$clientes = [
  1 => 'João Silva',
  2 => 'Maria Souza',
  3 => 'Carlos Lima',
  4 => 'Ana Pereira',
  5 => 'Beatriz Almeida',
  6 => 'Ricardo Santos',
];

$contas = [
  ['id' => 1, 'cliente_id' => 1, 'valor_total' => 150.00, 'metodo_pagamento' => 'Cartão', 'status_pagamento' => 'Pago', 'data_venda' => '2025-09-01'],
  ['id' => 2, 'cliente_id' => 2, 'valor_total' => 300.00, 'metodo_pagamento' => 'Boleto', 'status_pagamento' => 'Pendente', 'data_venda' => '2025-09-05'],
  ['id' => 3, 'cliente_id' => 3, 'valor_total' => 75.50, 'metodo_pagamento' => 'Pix', 'status_pagamento' => 'Pago', 'data_venda' => '2025-09-10'],
  ['id' => 4, 'cliente_id' => 4, 'valor_total' => 220.00, 'metodo_pagamento' => 'Cartão', 'status_pagamento' => 'Pendente', 'data_venda' => '2025-09-12'],
  ['id' => 5, 'cliente_id' => 5, 'valor_total' => 95.00, 'metodo_pagamento' => 'Pix', 'status_pagamento' => 'Pago', 'data_venda' => '2025-09-15'],
  ['id' => 6, 'cliente_id' => 6, 'valor_total' => 500.00, 'metodo_pagamento' => 'Boleto', 'status_pagamento' => 'Pendente', 'data_venda' => '2025-09-18'],
];

// Lógica de paginação
$itensPorPagina = 5;
$totalContas = count($contas);
$totalPaginas = ceil($totalContas / $itensPorPagina);
$paginaAtual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$inicio = ($paginaAtual - 1) * $itensPorPagina;
$contasPagina = array_slice($contas, $inicio, $itensPorPagina);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contas a Receber - Master Control</title>
  <script src="../../scripts/theme.js"></script>
  <link rel="stylesheet" href="../../styles/styles.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[var(--color-background)] group" data-current-page="contas">

  <div class="flex">
    <?php require_once '../../includes/components/sidebar.php'; ?>
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 z-40 lg:hidden group-[.sidebar-closed]:hidden"></div>
    <div class="flex-1 flex flex-col min-h-screen">
      <?php require_once '../../includes/components/header.php'; ?>

      <main class="p-4 md:p-8 transition-all duration-300 lg:ml-64 group-[.sidebar-closed]:lg:ml-0">

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
          <h1 class="text-3xl font-bold text-[var(--color-text-primary)]">Contas a Receber</h1>
          <button class="bg-[var(--color-primary)] text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:opacity-90 transition-opacity flex items-center gap-2">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Adicionar Conta
          </button>
        </div>

        <div class="mb-6">
          <input type="search" placeholder="Pesquisar por cliente..." class="search-input w-full sm:max-w-sm p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-surface)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>

        <div class="bg-[var(--color-surface)] p-4 sm:p-6 rounded-lg shadow-md">

          <!-- Cards para visualização mobile -->
          <div class="grid gap-4 sm:hidden searchable-container">
            <?php foreach ($contasPagina as $conta): ?>
              <div class="p-4 border border-[var(--color-border)] rounded-lg searchable-item">
                <h2 class="font-semibold text-[var(--color-text-primary)]"><?= htmlspecialchars($clientes[$conta['cliente_id']]) ?></h2>
                <p class="text-sm text-[var(--color-text-secondary)]">Valor: R$ <?= number_format($conta['valor_total'], 2, ',', '.') ?></p>
                <p class="text-sm text-[var(--color-text-secondary)]">Método: <?= htmlspecialchars($conta['metodo_pagamento']) ?></p>
                <p class="text-sm text-[var(--color-text-secondary)]">Data: <?= date('d/m/Y', strtotime($conta['data_venda'])) ?></p>
                <p class="text-sm text-[var(--color-text-secondary)]">
                  Status:
                  <?php if ($conta['status_pagamento'] === 'Pago'): ?>
                    <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Pago</span>
                  <?php else: ?>
                    <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pendente</span>
                  <?php endif; ?>
                </p>
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

          <!-- Tabela para visualização desktop -->
          <div class="overflow-x-auto hidden sm:block">
            <table class="w-full text-left text-sm sm:text-base min-w-[700px] searchable-table">
              <thead>
                <tr class="border-b border-[var(--color-border)]">
                  <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Cliente</th>
                  <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Valor</th>
                  <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Método</th>
                  <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Data da Venda</th>
                  <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Status</th>
                  <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($contasPagina as $conta): ?>
                  <tr class="border-b border-[var(--color-border)] hover:bg-[var(--color-background)] searchable-row">
                    <td class="p-3 font-medium text-[var(--color-text-primary)]"><?= htmlspecialchars($clientes[$conta['cliente_id']]) ?></td>
                    <td class="p-3 font-medium text-[var(--color-text-primary)]">R$ <?= number_format($conta['valor_total'], 2, ',', '.') ?></td>
                    <td class="p-3 text-[var(--color-text-secondary)]"><?= htmlspecialchars($conta['metodo_pagamento']) ?></td>
                    <td class="p-3 text-[var(--color-text-secondary)]"><?= date('d/m/Y', strtotime($conta['data_venda'])) ?></td>
                    <td class="p-3">
                      <?php if ($conta['status_pagamento'] === 'Pago'): ?>
                        <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Pago</span>
                      <?php else: ?>
                        <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pendente</span>
                      <?php endif; ?>
                    </td>
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

          <!-- Paginação -->
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