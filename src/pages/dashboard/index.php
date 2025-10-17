<?php
require_once '../../includes/auth_check.php';
require_once '../../../database/index.php';

$currentPage = "inicio";

$kpiSql = "
    SELECT
        SUM(valor_total) AS total_vendido,
        COUNT(CASE WHEN status_pagamento = 'PAGO' THEN 1 END) AS total_pagas,
        COUNT(CASE WHEN status_pagamento = 'PENDENTE' THEN 1 END) AS total_pendentes
    FROM vendas
";
$kpiResult = mysqli_query($conexao, $kpiSql);
$kpis = mysqli_fetch_assoc($kpiResult);

$totalVendas = $kpis['total_vendido'] ?? 0;
$totalPagas = $kpis['total_pagas'] ?? 0;
$totalPendentes = $kpis['total_pendentes'] ?? 0;

$vendasPorStatusData = [$totalPagas, $totalPendentes];


$vendas7diasSql = "
    SELECT
        DATE(data_venda) AS dia,
        SUM(valor_total) AS total
    FROM vendas
    WHERE data_venda >= CURDATE() - INTERVAL 7 DAY
    GROUP BY dia
    ORDER BY dia ASC
";
$vendas7diasResult = mysqli_query($conexao, $vendas7diasSql);
$vendas7dias = [];
while ($row = mysqli_fetch_assoc($vendas7diasResult)) {
  $vendas7dias[] = $row;
}

$vendasPorDiaLabels = [];
$vendasPorDiaData = [];
$periodo = new DatePeriod(
  new DateTime('-6 days'),
  new DateInterval('P1D'),
  new DateTime('+1 day')
);
$diasCompletos = [];
foreach ($periodo as $dia) {
  $diasCompletos[$dia->format('Y-m-d')] = 0;
}
foreach ($vendas7dias as $venda) {
  $diasCompletos[$venda['dia']] = $venda['total'];
}
foreach ($diasCompletos as $dia => $total) {
  $vendasPorDiaLabels[] = date('d/m', strtotime($dia));
  $vendasPorDiaData[] = $total;
}

$ultimasVendasSql = "
    SELECT
        v.id,
        c.nome AS cliente_nome,
        v.data_venda,
        v.status_pagamento,
        v.valor_total,
        GROUP_CONCAT(p.nome SEPARATOR ', ') AS produtos
    FROM vendas v
    JOIN clientes c ON v.cliente_id = c.id
    JOIN itens_venda iv ON v.id = iv.venda_id
    JOIN produtos p ON iv.produto_id = p.id
    GROUP BY v.id
    ORDER BY v.data_venda DESC
    LIMIT 5
";
$ultimasVendasResult = mysqli_query($conexao, $ultimasVendasSql);
$ultimasVendas = [];
while ($row = mysqli_fetch_assoc($ultimasVendasResult)) {
  $ultimasVendas[] = $row;
}

?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light-blue">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Master Control</title>
  <script src="../../scripts/theme.js"></script>
  <link rel="stylesheet" href="../../styles/styles.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    const vendasPorDiaLabels = <?php echo json_encode($vendasPorDiaLabels); ?>;
    const vendasPorDiaData = <?php echo json_encode($vendasPorDiaData); ?>;
    const vendasPorStatusData = <?php echo json_encode($vendasPorStatusData); ?>;
  </script>
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

        <?php if (empty($ultimasVendas)): ?>
          <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded-lg shadow-md" role="alert">
            <p class="font-bold">Nenhuma venda encontrada!</p>
            <p>Parece que você ainda não realizou nenhuma venda. 
              <a href="./vendas.php" class="font-bold underline">Que tal começar agora?</a> </p>
          </div>
        <?php else: ?>
          <div class="flex gap-2 mb-6">
            <button id="show-kpis" class="px-3 py-1 rounded bg-gray-300 text-gray-800 font-semibold">KPIs</button>
            <button id="show-charts" class="px-3 py-1 rounded bg-gray-300 text-gray-800 font-semibold">Gráficos</button>
          </div>

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

          <div id="charts-section" class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-8 mb-6 hidden">
            <div class="bg-[var(--color-surface)] p-4 sm:p-6 rounded-lg shadow-md">
              <h2 class="text-lg sm:text-xl font-semibold text-[var(--color-text-primary)] mb-3 sm:mb-4">Vendas nos Últimos 7 Dias</h2>
              <div class="w-full overflow-x-auto"><canvas id="vendasPorDiaChart"></canvas></div>
            </div>
            <div class="bg-[var(--color-surface)] p-4 sm:p-6 rounded-lg shadow-md">
              <h2 class="text-lg sm:text-xl font-semibold text-[var(--color-text-primary)] mb-3 sm:mb-4">Vendas por Status</h2>
              <div class="max-w-[200px] sm:max-w-xs mx-auto"><canvas id="vendasPorStatusChart"></canvas></div>
            </div>
          </div>

          <div class="bg-[var(--color-surface)] p-4 sm:p-6 rounded-lg shadow-md">
            <h2 class="text-lg sm:text-xl font-semibold text-[var(--color-text-primary)] mb-3 sm:mb-4">Últimas 5 Vendas</h2>
            <div class="overflow-x-auto">
              <table class="w-full text-left text-sm sm:text-base">
                <thead>
                  <tr class="border-b border-[var(--color-border)]">
                    <th class="p-2 sm:p-3 font-semibold text-[var(--color-text-secondary)]">Produtos</th>
                    <th class="p-2 sm:p-3 font-semibold text-[var(--color-text-secondary)] hidden xs:table-cell">Cliente</th>
                    <th class="p-2 sm:p-3 font-semibold text-[var(--color-text-secondary)] hidden sm:table-cell">Data</th>
                    <th class="p-2 sm:p-3 font-semibold text-[var(--color-text-secondary)]">Status</th>
                    <th class="p-2 sm:p-3 font-semibold text-[var(--color-text-secondary)]">Valor</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($ultimasVendas as $venda): ?>
                    <tr class="border-b border-[var(--color-border)] hover:bg-[var(--color-background)]">
                      <td class="p-2 sm:p-3 text-[var(--color-text-primary)] font-medium"><?php echo htmlspecialchars($venda['produtos']); ?></td>
                      <td class="p-2 sm:p-3 text-[var(--color-text-secondary)] hidden xs:table-cell"><?php echo htmlspecialchars($venda['cliente_nome']); ?></td>
                      <td class="p-2 sm:p-3 text-[var(--color-text-secondary)] hidden sm:table-cell"><?php echo date('d/m/Y', strtotime($venda['data_venda'])); ?></td>
                      <td class="p-2 sm:p-3">
                        <?php if ($venda['status_pagamento'] === 'PAGO'): ?>
                          <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Pago</span>
                        <?php else: ?>
                          <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pendente</span>
                        <?php endif; ?>
                      </td>
                      <td class="p-2 sm:p-3 text-[var(--color-text-primary)] font-medium">R$ <?php echo number_format($venda['valor_total'], 2, ',', '.'); ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        <?php endif; ?>
      </main>
    </div>
  </div>

  <script>
    // Restaurar estado da sidebar imediatamente
    document.addEventListener('DOMContentLoaded', function() {
      const savedSidebarState = localStorage.getItem('sidebarState');
      if (savedSidebarState === 'closed') {
        document.body.classList.add('sidebar-closed');
      }
    });
  </script>
  <script src="../../scripts/formatters.js"></script>
  <script src="../../scripts/dashboard.js"></script>
</body>

</html>