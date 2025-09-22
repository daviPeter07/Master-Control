<?php
require_once '../../includes/auth_check.php';
require_once '../../../database/index.php'; // Sua conexão com o banco
$currentPage = "vendas";

// --- Lógica de Paginação com o Banco de Dados ---

// 1. Definir quantos itens por página
$itensPorPagina = 5;

// 2. Contar o total de vendas no banco para calcular o número de páginas
$totalVendasSql = "SELECT COUNT(*) AS total FROM vendas";
$totalResult = mysqli_query($conexao, $totalVendasSql);
$totalVendas = mysqli_fetch_assoc($totalResult)['total'];
$totalPaginas = ceil($totalVendas / $itensPorPagina);

// 3. Pegar a página atual da URL, garantindo que seja um número válido
$paginaAtual = isset($_GET['pagina']) ? max(1, min((int)$_GET['pagina'], $totalPaginas)) : 1;

// 4. Calcular o offset (ponto de início) para a query SQL
$inicio = ($paginaAtual - 1) * $itensPorPagina;

// 5. Query para buscar as vendas da página atual, já com os nomes de produtos e clientes
// Usamos prepared statements para segurança ao passar os limites da paginação.
$vendasPaginaSql = "
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
    LIMIT ?, ?
";

// Prepara a consulta
$stmt = mysqli_prepare($conexao, $vendasPaginaSql);
// Associa os parâmetros (i = integer)
mysqli_stmt_bind_param($stmt, "ii", $inicio, $itensPorPagina);
// Executa a consulta
mysqli_stmt_execute($stmt);
// Pega os resultados
$result = mysqli_stmt_get_result($stmt);

// Monta o array com as vendas da página
$vendasPagina = [];
while ($row = mysqli_fetch_assoc($result)) {
  $vendasPagina[] = $row;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vendas - Master Control</title>
  <script src="../../scripts/theme.js"></script>
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
          <input type="search" id="searchInput" placeholder="Pesquisar por produto ou cliente..." class="search-input w-full sm:max-w-sm p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-surface)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>

        <div class="bg-[var(--color-surface)] p-4 sm:p-6 rounded-lg shadow-md">
          <?php if (empty($vendasPagina)): ?>
            <div class="text-center py-8">
              <p class="text-[var(--color-text-secondary)]">Nenhuma venda encontrado.</p>
              <p class="text-[var(--color-text-secondary)] mt-2">Clique em "Adicionar Venda" para começar.</p>
            </div>
          <?php else: ?>
            <div class="grid gap-4 sm:hidden searchable-container">
              <?php foreach ($vendasPagina as $venda): ?>
                <div class="p-4 border border-[var(--color-border)] rounded-lg searchable-item">
                  <h2 class="font-semibold text-[var(--color-text-primary)]"><?= htmlspecialchars($venda['produtos']) ?></h2>
                  <p class="text-sm text-[var(--color-text-secondary)]">Cliente: <?= htmlspecialchars($venda['cliente_nome']) ?></p>
                  <p class="text-sm text-[var(--color-text-secondary)]">Data: <?= date('d/m/Y', strtotime($venda['data_venda'])) ?></p>
                  <p class="text-sm text-[var(--color-text-secondary)]">Valor: R$ <?= number_format($venda['valor_total'], 2, ',', '.') ?></p>
                  <p class="text-sm text-[var(--color-text-secondary)]">
                    Status:
                    <?php if ($venda['status_pagamento'] === 'PAGO'): ?>
                      <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Pago</span>
                    <?php else: ?>
                      <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pendente</span>
                    <?php endif; ?>
                  </p>
                  <div class="flex gap-2 mt-2">
                    <a href="#" class="bg-blue-500 text-white px-3 py-1 text-sm rounded-lg flex items-center gap-1 hover:bg-blue-600">Editar</a>
                    <a href="#" class="bg-red-500 text-white px-3 py-1 text-sm rounded-lg flex items-center gap-1 hover:bg-red-600">Deletar</a>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <div class="overflow-x-auto hidden sm:block">
              <table class="w-full text-left text-sm sm:text-base min-w-[700px] searchable-table">
                <thead>
                  <tr class="border-b border-[var(--color-border)]">
                    <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Produtos</th>
                    <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Cliente</th>
                    <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Data</th>
                    <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Status</th>
                    <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Valor</th>
                    <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($vendasPagina as $venda): ?>
                    <tr class="border-b border-[var(--color-border)] hover:bg-[var(--color-background)] searchable-row">
                      <td class="p-3 font-medium text-[var(--color-text-primary)]"><?= htmlspecialchars($venda['produtos']) ?></td>
                      <td class="p-3 text-[var(--color-text-secondary)]"><?= htmlspecialchars($venda['cliente_nome']) ?></td>
                      <td class="p-3 text-[var(--color-text-secondary)]"><?= date('d/m/Y', strtotime($venda['data_venda'])) ?></td>
                      <td class="p-3">
                        <?php if ($venda['status_pagamento'] === 'PAGO'): ?>
                          <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Pago</span>
                        <?php else: ?>
                          <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pendente</span>
                        <?php endif; ?>
                      </td>
                      <td class="p-3 font-medium text-[var(--color-text-primary)]">R$ <?= number_format($venda['valor_total'], 2, ',', '.') ?></td>
                      <td class="p-3 flex gap-2">
                        <a href="#" class="bg-blue-500 text-white px-3 py-1 rounded-lg flex items-center gap-1 hover:bg-blue-600 text-xs">Editar</a>
                        <a href="#" class="bg-red-500 text-white px-3 py-1 rounded-lg flex items-center gap-1 hover:bg-red-600 text-xs">Deletar</a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>

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
  <script src="../../scripts/dashboard.js"></script>
</body>

</html>