<?php
require_once '../../includes/auth_check.php';
$currentPage = "vendas";

// mock de vendas
$vendas = [
  ['produto' => 'Perfume Kaiak', 'cliente' => 'Ana Silva', 'data' => '21/09/2025', 'status' => 'Pago', 'valor' => 120.50],
  ['produto' => 'Desodorante Corporal', 'cliente' => 'Bruno Costa', 'data' => '21/09/2025', 'status' => 'Pendente', 'valor' => 45.00],
  ['produto' => 'Sabonete Líquido', 'cliente' => 'Carla Dias', 'data' => '20/09/2025', 'status' => 'Pago', 'valor' => 25.75],
  ['produto' => 'Body Splash', 'cliente' => 'Daniel Farias', 'data' => '19/09/2025', 'status' => 'Pago', 'valor' => 60.00],
  ['produto' => 'Creme de Pentear', 'cliente' => 'Fernanda Lima', 'data' => '18/09/2025', 'status' => 'Pendente', 'valor' => 30.00],
  ['produto' => 'Shampoo Antiqueda', 'cliente' => 'Gabriel Rocha', 'data' => '17/09/2025', 'status' => 'Pago', 'valor' => 50.00],
  ['produto' => 'Condicionador', 'cliente' => 'Helena Souza', 'data' => '16/09/2025', 'status' => 'Pago', 'valor' => 45.00],
];

// paginação
$itensPorPagina = 5;
$totalVendas = count($vendas);
$totalPaginas = ceil($totalVendas / $itensPorPagina);
$paginaAtual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$inicio = ($paginaAtual - 1) * $itensPorPagina;
$vendasPagina = array_slice($vendas, $inicio, $itensPorPagina);
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light-blue">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vendas - Master Control</title>
  <link rel="stylesheet" href="../../styles/styles.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[var(--color-background)] group" data-current-page="vendas">

  <div class="flex">
    <?php require_once '../../includes/components/sidebar.php'; ?>
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 z-40 lg:hidden group-[.sidebar-closed]:hidden"></div>
    <div class="flex-1 flex flex-col min-h-screen">
      <?php require_once '../../includes/components/header.php'; ?>

      <main class="p-4 md:p-8 transition-all duration-300 lg:ml-64 group-[.sidebar-closed]:lg:ml-0">

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
          <h1 class="text-3xl font-bold text-[var(--color-text-primary)]">Vendas Gerais</h1>
          <button class="bg-[var(--color-primary)] text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:opacity-90 transition-opacity flex items-center gap-2">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Adicionar Venda
          </button>
        </div>

        <div class="mb-6">
          <input type="search" placeholder="Pesquisar por produto ou cliente..." class="search-input w-full sm:max-w-sm p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-surface)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>

        <div class="bg-[var(--color-surface)] p-4 sm:p-6 rounded-lg shadow-md">

          <!-- Cards mobile -->
          <div class="grid gap-4 sm:hidden">
            <?php foreach ($vendasPagina as $venda): ?>
              <div class="p-4 border border-[var(--color-border)] rounded-lg">
                <h2 class="font-semibold text-[var(--color-text-primary)]"><?= htmlspecialchars($venda['produto']) ?></h2>
                <p class="text-sm text-[var(--color-text-secondary)]">Cliente: <?= htmlspecialchars($venda['cliente']) ?></p>
                <p class="text-sm text-[var(--color-text-secondary)]">Data: <?= htmlspecialchars($venda['data']) ?></p>
                <p class="text-sm text-[var(--color-text-secondary)]">
                  Status:
                  <?php if ($venda['status'] === 'Pago'): ?>
                    <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Pago</span>
                  <?php else: ?>
                    <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pendente</span>
                  <?php endif; ?>
                </p>
                <p class="text-sm text-[var(--color-text-secondary)]">Valor: R$ <?= number_format($venda['valor'], 2, ',', '.') ?></p>
                <div class="flex gap-2 mt-2">
                  <a href="#" class="bg-blue-500 text-white px-3 py-1 rounded-lg flex items-center gap-1 hover:bg-blue-600">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M4 20h4l12-12-4-4-12 12v4z" />
                    </svg>
                    Editar
                  </a>
                  <a href="#" class="bg-red-500 text-white px-3 py-1 rounded-lg flex items-center gap-1 hover:bg-red-600">
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
            <table class="w-full text-left text-sm sm:text-base min-w-[700px]">
              <thead>
                <tr class="border-b border-[var(--color-border)]">
                  <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Produto</th>
                  <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Cliente</th>
                  <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Data</th>
                  <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Status</th>
                  <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Valor</th>
                  <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($vendasPagina as $venda): ?>
                  <tr class="border-b border-[var(--color-border)] hover:bg-[var(--color-background)]">
                    <td class="p-3 font-medium text-[var(--color-text-primary)]"><?= htmlspecialchars($venda['produto']) ?></td>
                    <td class="p-3 text-[var(--color-text-secondary)]"><?= htmlspecialchars($venda['cliente']) ?></td>
                    <td class="p-3 text-[var(--color-text-secondary)]"><?= htmlspecialchars($venda['data']) ?></td>
                    <td class="p-3">
                      <?php if ($venda['status'] === 'Pago'): ?>
                        <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Pago</span>
                      <?php else: ?>
                        <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pendente</span>
                      <?php endif; ?>
                    </td>
                    <td class="p-3 font-medium text-[var(--color-text-primary)]">R$ <?= number_format($venda['valor'], 2, ',', '.') ?></td>
                    <td class="p-3 flex gap-2">
                      <a href="#" class="bg-blue-500 text-white px-3 py-1 rounded-lg flex items-center gap-1 hover:bg-blue-600">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M4 20h4l12-12-4-4-12 12v4z" />
                        </svg>
                        Editar
                      </a>
                      <a href="#" class="bg-red-500 text-white px-3 py-1 rounded-lg flex items-center gap-1 hover:bg-red-600">
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
            <span class="text-[var(--color-text-secondary)]">Página <?= $paginaAtual ?> de <?= $totalPaginas ?></span>
            <a href="?pagina=<?= min($totalPaginas, $paginaAtual + 1) ?>" class="pag-next <?= $paginaAtual >= $totalPaginas ? 'disabled' : '' ?>">Próximo</a>
          </div>

        </div>

      </main>
    </div>
  </div>

  <script src="../../scripts/dashboard.js"></script>
</body>

</html>