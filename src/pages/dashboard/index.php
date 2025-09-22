<?php
require_once '../../includes/auth_check.php';
$currentPage = "inicio";

// Mock data
$ultimasVendas = [
  ['id' => 1, 'produto' => 'Perfume Kaiak', 'cliente' => 'Ana Silva', 'data' => '21/09/2025', 'status' => 'Pago', 'valor' => 120.50],
  ['id' => 2, 'produto' => 'Desodorante Corporal', 'cliente' => 'Bruno Costa', 'data' => '21/09/2025', 'status' => 'Pendente', 'valor' => 45.00],
  ['id' => 3, 'produto' => 'Sabonete Líquido', 'cliente' => 'Carla Dias', 'data' => '20/09/2025', 'status' => 'Pago', 'valor' => 25.75],
  ['id' => 4, 'produto' => 'Body Splash', 'cliente' => 'Daniel Farias', 'data' => '19/09/2025', 'status' => 'Pago', 'valor' => 60.00],
  ['id' => 5, 'produto' => 'Creme de Pentear', 'cliente' => 'Fernanda Lima', 'data' => '18/09/2025', 'status' => 'Pendente', 'valor' => 30.00],
];

// KPIs
$totalVendas = array_sum(array_column($ultimasVendas, 'valor'));
$totalPagas = count(array_filter($ultimasVendas, fn($v) => $v['status'] === 'Pago'));
$totalPendentes = count(array_filter($ultimasVendas, fn($v) => $v['status'] === 'Pendente'));
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light-blue">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Master Control</title>
  <link rel="stylesheet" href="../../styles/styles.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[var(--color-background)] group" data-current-page="inicio">

  <div class="flex">
    <?php require_once '../../includes/components/sidebar.php'; ?>
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 z-40 lg:hidden group-[.sidebar-closed]:hidden"></div>
    <div class="flex-1">
      <?php require_once '../../includes/components/header.php'; ?>

      <main class="p-3 sm:p-6 transition-all duration-300 lg:ml-64 group-[.sidebar-closed]:lg:ml-0">
        <h1 class="text-2xl sm:text-3xl font-bold text-[var(--color-text-primary)] mb-6 sm:mb-8">
          Olá, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!
        </h1>

        <!-- Botões para alternar visualização -->
        <div class="flex gap-2 mb-6">
          <button id="show-kpis" class="px-3 py-1 rounded bg-gray-300 text-gray-800 font-semibold">KPIs</button>
          <button id="show-charts" class="px-3 py-1 rounded bg-gray-300 text-gray-800 font-semibold">Gráficos</button>
        </div>

        <!-- KPIs -->
        <div id="kpis-section" class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-6 hidden">
          <div class="bg-[var(--color-surface)] p-4 sm:p-6 rounded-lg shadow-md">
            <h2 class="text-sm font-semibold text-[var(--color-text-secondary)]">Total Vendido</h2>
            <p class="text-xl sm:text-2xl font-bold text-green-700">R$ <?php echo number_format($totalVendas, 2, ',', '.'); ?></p>
          </div>
          <div class="bg-[var(--color-surface)] p-4 sm:p-6 rounded-lg shadow-md">
            <h2 class="text-sm font-semibold text-[var(--color-text-secondary)]">Vendas Pagas</h2>
            <p class="text-xl sm:text-2xl font-bold text-blue-700"><?php echo $totalPagas; ?></p>
          </div>
          <div class="bg-[var(--color-surface)] p-4 sm:p-6 rounded-lg shadow-md">
            <h2 class="text-sm font-semibold text-[var(--color-text-secondary)]">Vendas Pendentes</h2>
            <p class="text-xl sm:text-2xl font-bold text-red-700"><?php echo $totalPendentes; ?></p>
          </div>
        </div>

        <!-- Gráficos -->
        <div id="charts-section" class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-8 mb-6 hidden">
          <div class="bg-[var(--color-surface)] p-4 sm:p-6 rounded-lg shadow-md">
            <h2 class="text-lg sm:text-xl font-semibold text-[var(--color-text-primary)] mb-3 sm:mb-4">Vendas nos Últimos 7 Dias</h2>
            <div class="w-full overflow-x-auto">
              <canvas id="vendasPorDiaChart"></canvas>
            </div>
          </div>
          <div class="bg-[var(--color-surface)] p-4 sm:p-6 rounded-lg shadow-md">
            <h2 class="text-lg sm:text-xl font-semibold text-[var(--color-text-primary)] mb-3 sm:mb-4">Vendas por Status</h2>
            <div class="max-w-[200px] sm:max-w-xs mx-auto">
              <canvas id="vendasPorStatusChart"></canvas>
            </div>
          </div>
        </div>

        <!-- Últimas vendas -->
        <div class="bg-[var(--color-surface)] p-4 sm:p-6 rounded-lg shadow-md">
          <h2 class="text-lg sm:text-xl font-semibold text-[var(--color-text-primary)] mb-3 sm:mb-4">Últimas Vendas</h2>
          <div class="overflow-x-auto">
            <table class="w-full text-left text-sm sm:text-base">
              <thead>
                <tr class="border-b border-[var(--color-border)]">
                  <th class="p-2 sm:p-3 font-semibold text-[var(--color-text-secondary)]">Produto</th>
                  <th class="p-2 sm:p-3 font-semibold text-[var(--color-text-secondary)] hidden xs:table-cell">Cliente</th>
                  <th class="p-2 sm:p-3 font-semibold text-[var(--color-text-secondary)] hidden sm:table-cell">Data</th>
                  <th class="p-2 sm:p-3 font-semibold text-[var(--color-text-secondary)]">Status</th>
                  <th class="p-2 sm:p-3 font-semibold text-[var(--color-text-secondary)]">Valor</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($ultimasVendas as $venda): ?>
                  <tr class="border-b border-[var(--color-border)] hover:bg-[var(--color-background)]">
                    <td class="p-2 sm:p-3 text-[var(--color-text-primary)] font-medium"><?php echo htmlspecialchars($venda['produto']); ?></td>
                    <td class="p-2 sm:p-3 text-[var(--color-text-secondary)] hidden xs:table-cell"><?php echo htmlspecialchars($venda['cliente']); ?></td>
                    <td class="p-2 sm:p-3 text-[var(--color-text-secondary)] hidden sm:table-cell"><?php echo htmlspecialchars($venda['data']); ?></td>
                    <td class="p-2 sm:p-3">
                      <?php if ($venda['status'] === 'Pago'): ?>
                        <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Pago</span>
                      <?php else: ?>
                        <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pendente</span>
                      <?php endif; ?>
                    </td>
                    <td class="p-2 sm:p-3 text-[var(--color-text-primary)] font-medium">R$ <?php echo number_format($venda['valor'], 2, ',', '.'); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script src="../../scripts/dashboard.js"></script>
</body>

</html>