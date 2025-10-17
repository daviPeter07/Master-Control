<?php
require_once '../../includes/auth_check.php';
require_once '../../../database/index.php';
$currentPage = "contas";

// buscar dados
$clientesResult = mysqli_query($conexao, "SELECT id, nome FROM clientes ORDER BY nome ASC");
$clientes = mysqli_fetch_all($clientesResult, MYSQLI_ASSOC);
$produtosResult = mysqli_query($conexao, "SELECT id, nome, valor_venda FROM produtos WHERE quantidade > 0 ORDER BY nome ASC");
$produtos = mysqli_fetch_all($produtosResult, MYSQLI_ASSOC);

// Definir itens por página
$itensPorPagina = 5;

// Contar o total de contas (vendas) no banco
$totalContasSql = "SELECT COUNT(id) AS total FROM vendas";
$totalResult = mysqli_query($conexao, $totalContasSql);
$totalContas = mysqli_fetch_assoc($totalResult)['total'];
$totalPaginas = $totalContas > 0 ? ceil($totalContas / $itensPorPagina) : 1;
$paginaAtual = isset($_GET['pagina']) ? max(1, min((int)$_GET['pagina'], $totalPaginas)) : 1;
$inicio = ($paginaAtual - 1) * $itensPorPagina;

// Query para buscar as contas da página atual, com todos os dados necessários
$contasPaginaSql = "
    SELECT
        v.id,
        v.cliente_id,
        v.valor_total,
        v.metodo_pagamento,
        v.status_pagamento,
        v.data_venda,
        c.nome AS cliente_nome,
        (SELECT CONCAT('[', GROUP_CONCAT(JSON_OBJECT('produto_id', iv.produto_id, 'quantidade', iv.quantidade)), ']')
         FROM itens_venda iv WHERE iv.venda_id = v.id) AS itens_json
    FROM vendas v
    JOIN clientes c ON v.cliente_id = c.id
    ORDER BY v.data_venda DESC
    LIMIT ?, ?
";

$stmt = mysqli_prepare($conexao, $contasPaginaSql);
mysqli_stmt_bind_param($stmt, "ii", $inicio, $itensPorPagina);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$contasPagina = mysqli_fetch_all($result, MYSQLI_ASSOC);
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

        <!--Title e button para add item-->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
          <h1 class="text-3xl font-bold text-[var(--color-text-primary)]">Contas a Receber</h1>
          <button  id="open-add-modal-btn" class="bg-[var(--color-primary)] text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:opacity-90 transition-opacity flex items-center gap-2">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Adicionar Conta
          </button>
        </div>

        <!--Input pesquisa-->
        <div class="mb-6">
          <input type="search" id="searchInput" placeholder="Pesquisar por cliente..." class="search-input w-full sm:max-w-sm p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-surface)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>

        <!--No Data-->
        <div class="bg-[var(--color-surface)] p-4 sm:p-6 rounded-lg shadow-md">
          <?php if (empty($contasPagina)): ?>
            <div class="text-center py-8">
              <p class="text-[var(--color-text-secondary)]">Nenhuma conta a receber encontrado.</p>
              <p class="text-[var(--color-text-secondary)] mt-2">Clique em "Adicionar Conta" para começar.</p>
            </div>
          <?php else: ?>

            <!-- Cards mobile -->
            <div class="grid gap-4 sm:hidden searchable-container">
              <?php foreach ($contasPagina as $conta): ?>
                <div class="p-4 border border-[var(--color-border)] rounded-lg searchable-item">
                  <h2 class="font-semibold text-[var(--color-text-primary)] mb-2">
                    <?= htmlspecialchars($conta['cliente_nome']) ?>
                  </h2>
                  <p class="text-sm text-[var(--color-text-secondary)]">
                    Valor: R$ <?= number_format($conta['valor_total'], 2, ',', '.') ?>
                  </p>
                  <p class="text-sm text-[var(--color-text-secondary)]">
                    Método: <?= htmlspecialchars($conta['metodo_pagamento']) ?>
                  </p>
                  <p class="text-sm text-[var(--color-text-secondary)]">
                    Data: <?= date('d/m/Y', strtotime($conta['data_venda'])) ?>
                  </p>
                  <p class="text-sm text-[var(--color-text-secondary)]">
                    Status:
                    <?php if ($conta['status_pagamento'] === 'PAGO'): ?>
                      <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Pago</span>
                    <?php else: ?>
                      <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pendente</span>
                    <?php endif; ?>
                  </p>

                  <!--Actions mobile-->
                  <div class="flex gap-2 mt-2">

                    <!--Actions edit mobile-->
                    <button class="open-edit-modal-btn bg-blue-500 text-white px-3 py-1 rounded-lg text-xs"
                      data-id="<?= $conta['id'] ?>"
                      data-cliente-id="<?= $conta['cliente_id'] ?>"
                      data-metodo="<?= $conta['metodo_pagamento'] ?>"
                      data-status="<?= $conta['status_pagamento'] ?>"
                      data-itens='<?= htmlspecialchars($conta['itens_json'] ?? '[]') ?>'>
                      Editar
                    </button>

                    <!--Actions delete mobile-->
                    <button class="open-delete-modal-btn bg-red-500 text-white px-3 py-1 rounded-lg text-xs"
                      data-id="<?= $conta['id'] ?>"
                      data-nome="Conta #<?= $conta['id'] ?>">
                      Deletar
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
                      <td class="p-3 font-medium text-[var(--color-text-primary)]">
                        <?= htmlspecialchars($conta['cliente_nome']) ?>
                      </td>
                      <td class="p-3 font-medium text-[var(--color-text-primary)]">
                        R$ <?= number_format($conta['valor_total'], 2, ',', '.') ?>
                      </td>
                      <td class="p-3 text-[var(--color-text-secondary)]">
                        <?= htmlspecialchars($conta['metodo_pagamento']) ?>
                      </td>
                      <td class="p-3 text-[var(--color-text-secondary)]">
                        <?= date('d/m/Y', strtotime($conta['data_venda'])) ?>
                      </td>
                      <td class="p-3">
                        <?php if ($conta['status_pagamento'] === 'PAGO'): ?>
                          <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Pago</span>
                        <?php else: ?>
                          <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pendente</span>
                        <?php endif; ?>
                      </td>

                      <td class="p-3 flex gap-2">

                        <!--Actions edit-->
                        <button class="open-edit-modal-btn bg-blue-500 text-white px-3 py-1 rounded-lg text-xs"
                          data-id="<?= $conta['id'] ?>"
                          data-cliente-id="<?= $conta['cliente_id'] ?>"
                          data-metodo="<?= $conta['metodo_pagamento'] ?>"
                          data-status="<?= $conta['status_pagamento'] ?>"
                          data-itens='<?= htmlspecialchars($conta['itens_json'] ?? '[]') ?>'>
                          Editar
                        </button>

                        <!--Actions delete-->
                        <button class="open-delete-modal-btn bg-red-500 text-white px-3 py-1 rounded-lg text-xs"
                          data-id="<?= $conta['id'] ?>"
                          data-nome="Conta #<?= $conta['id'] ?>">
                          Deletar
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
  <script src="../../scripts/dashboard.js"></script>
</body>

</html>