<?php
require_once '../../includes/auth_check.php';
require_once '../../../database/index.php';
$currentPage = "vendas";

// Busca clientes e produtos para preencher os <select>
$clientesResult = mysqli_query($conexao, "SELECT id, nome FROM clientes ORDER BY nome ASC");
$clientes = mysqli_fetch_all($clientesResult, MYSQLI_ASSOC);
$produtosResult = mysqli_query($conexao, "SELECT id, nome, valor_venda FROM produtos ORDER BY nome ASC");
$produtos = mysqli_fetch_all($produtosResult, MYSQLI_ASSOC);

// Paginação (sem alterações)
$itensPorPagina = 5;
$totalVendasSql = "SELECT COUNT(*) AS total FROM vendas";
$totalResult = mysqli_query($conexao, $totalVendasSql);
$totalVendas = mysqli_fetch_assoc($totalResult)['total'];
$totalPaginas = $totalVendas > 0 ? ceil($totalVendas / $itensPorPagina) : 1;
$paginaAtual = isset($_GET['pagina']) ? max(1, min((int)$_GET['pagina'], $totalPaginas)) : 1;
$inicio = ($paginaAtual - 1) * $itensPorPagina;

// A subquery com JSON_OBJECT cria um array de itens para cada venda
$vendasPaginaSql = "
    SELECT
        v.id, v.cliente_id, v.data_venda, v.status_pagamento, v.metodo_pagamento, v.valor_total,
        COALESCE(c.nome, 'Cliente Avulso') AS cliente_nome,
        GROUP_CONCAT(DISTINCT COALESCE(p.nome, ivl.nome_produto) SEPARATOR ', ') AS produtos,
        (SELECT CONCAT('[', GROUP_CONCAT(JSON_OBJECT('produto_id', iv.produto_id, 'quantidade', iv.quantidade)), ']')
         FROM itens_venda iv WHERE iv.venda_id = v.id) AS itens_json
    FROM vendas v
    LEFT JOIN clientes c ON v.cliente_id = c.id
    LEFT JOIN itens_venda iv ON v.id = iv.venda_id
    LEFT JOIN produtos p ON iv.produto_id = p.id
    LEFT JOIN itens_venda_livres ivl ON v.id = ivl.venda_id
    GROUP BY v.id
    ORDER BY v.data_venda DESC
    LIMIT ?, ?
";
$stmt = mysqli_prepare($conexao, $vendasPaginaSql);
mysqli_stmt_bind_param($stmt, "ii", $inicio, $itensPorPagina);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$vendasPagina = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vendas - Master Control</title>
  <script src="../../scripts/theme.js"></script>
  <link rel="stylesheet" href="/src/styles/styles.css">
  <link rel="stylesheet" href="/src/styles/tailwind.css">
</head>

<body class="bg-[var(--color-background)] group" data-current-page="vendas">

  <div class="flex">
    <?php require_once '../../includes/components/sidebar.php'; ?>
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 z-40 lg:hidden group-[.sidebar-closed]:hidden"></div>
    <div class="flex-1 flex flex-col min-h-screen">
      <?php require_once '../../includes/components/header.php'; ?>

      <main class="p-4 md:p-8 transition-all duration-300 lg:ml-64 group-[.sidebar-closed]:lg:ml-0">

        <!--Title e button para add item-->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
          <h1 class="text-3xl font-bold text-[var(--color-text-primary)]">Vendas Gerais</h1>
          <button id="open-add-modal-btn" class="bg-[var(--color-primary)] text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:opacity-90 transition-opacity flex items-center gap-2">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Adicionar Venda
          </button>
        </div>

        <!--Input pesquisa-->
        <div class="mb-6">
          <input type="search" id="searchInput" placeholder="Pesquisar por produto ou cliente..." class="search-input w-full sm:max-w-sm p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-surface)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>

        <!--No Data-->
        <div class="bg-[var(--color-surface)] p-4 sm:p-6 rounded-lg shadow-md">
          <?php if (empty($vendasPagina)): ?>
            <div class="text-center py-8">
              <p class="text-[var(--color-text-secondary)]">Nenhuma venda encontrada.</p>
              <p class="text-[var(--color-text-secondary)] mt-2">Clique em "Adicionar Venda" para começar.</p>
            </div>
          <?php else: ?>

            <!-- Cards Mobile -->
            <div class="grid gap-4 sm:hidden searchable-container">
              <?php foreach ($vendasPagina as $venda): ?>
                <div class="p-4 border border-[var(--color-border)] rounded-lg searchable-item">

                  <h2 class="font-semibold text-[var(--color-text-primary)] mb-2">
                    <?= htmlspecialchars($venda['produtos'] ?? 'Venda sem itens') ?>
                  </h2>

                  <p class="text-sm text-[var(--color-text-secondary)]">
                    <strong>Cliente:</strong> <?= htmlspecialchars($venda['cliente_nome']) ?>
                  </p>
                  <p class="text-sm text-[var(--color-text-secondary)]">
                    <strong>Valor:</strong> R$ <?= number_format($venda['valor_total'], 2, ',', '.') ?>
                  </p>
                  <p class="text-sm text-[var(--color-text-secondary)]">
                    <strong>Data:</strong> <?= date('d/m/Y', strtotime($venda['data_venda'])) ?>
                  </p>
                  <p class="text-sm text-[var(--color-text-secondary)]">
                    <strong>Status:</strong>
                    <?php if ($venda['status_pagamento'] === 'PAGO'): ?>
                      <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Pago</span>
                    <?php else: ?>
                      <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pendente</span>
                    <?php endif; ?>
                  </p>

                  <!--Actions mobile-->
                  <div class="flex gap-2 mt-2">
                    <!--Actions edit mobile-->
                    <button class="open-edit-modal-btn bg-blue-500 text-white px-3 py-1 rounded-lg text-xs"
                      data-id="<?= $venda['id'] ?>"
                      data-cliente-id="<?= $venda['cliente_id'] ?>"
                      data-metodo="<?= $venda['metodo_pagamento'] ?>"
                      data-status="<?= $venda['status_pagamento'] ?>"
                      data-itens='<?= htmlspecialchars($venda['itens_json']) ?>'> Editar
                    </button>

                    <!--Actions delete mobile-->
                    <button class="open-delete-modal-btn bg-red-500 text-white px-3 py-1 rounded-lg text-xs"
                      data-id="<?= $venda['id'] ?>"
                      data-nome="Venda #<?= $venda['id'] ?>"> Deletar
                    </button>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- Tabela Desktop -->
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
                      <td class="p-3 font-medium text-[var(--color-text-primary)]">
                        <?= htmlspecialchars($venda['produtos']) ?>
                      </td>
                      <td class="p-3 text-[var(--color-text-secondary)]">
                        <?= htmlspecialchars($venda['cliente_nome']) ?>
                      </td>
                      <td class="p-3 text-[var(--color-text-secondary)]">
                        <?= date('d/m/Y', strtotime($venda['data_venda'])) ?>
                      </td>
                      <td class="p-3">
                        <?php if ($venda['status_pagamento'] === 'PAGO'): ?>
                          <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Pago</span>
                        <?php else: ?>
                          <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pendente</span>
                        <?php endif; ?>
                      </td>
                      <td class="p-3 font-medium text-[var(--color-text-primary)]">
                        R$ <?= number_format($venda['valor_total'], 2, ',', '.') ?>
                      </td>

                      <td class="p-3 flex gap-2">
                        <!--Actions edit-->
                        <button class="open-edit-modal-btn bg-blue-500 text-white px-3 py-1 rounded-lg text-xs"
                          data-id="<?= $venda['id'] ?>"
                          data-cliente-id="<?= $venda['cliente_id'] ?>"
                          data-metodo="<?= $venda['metodo_pagamento'] ?>"
                          data-status="<?= $venda['status_pagamento'] ?>"
                          data-itens='<?= htmlspecialchars($venda['itens_json']) ?>'> Editar
                        </button>

                        <!--Actions delete-->
                        <button class="open-delete-modal-btn bg-red-500 text-white px-3 py-1 rounded-lg text-xs"
                          data-id="<?= $venda['id'] ?>"
                          data-nome="Venda #<?= $venda['id'] ?>"> Deletar
                        </button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>

            <!-- Paginação -->
            <div class="mt-4 flex justify-between items-center">
              <a href="?pagina=<?= max(1, $paginaAtual - 1) ?>" class="pag-prev <?= $paginaAtual <= 1 ? 'disabled' : '' ?>">
                Anterior
              </a>

              <span class="text-sm text-[var(--color-text-secondary)]">
                Página <?= $paginaAtual ?> de <?= $totalPaginas ?>
              </span>

              <a href="?pagina=<?= min($totalPaginas, $paginaAtual + 1) ?>" class="pag-next <?= $paginaAtual >= $totalPaginas ? 'disabled' : '' ?>">
                Próximo
              </a>
            </div>
          <?php endif; ?>
        </div>
      </main>
    </div>
  </div>

  <?php
  require_once '../../includes/components/modal/vendas/modal_add_venda.php';
  require_once '../../includes/components/modal/vendas/modal_edit_venda.php';
  require_once '../../includes/components/modal/modal_delete_confirm.php';
  ?>

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