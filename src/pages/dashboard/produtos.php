<?php
require_once '../../includes/auth_check.php';
require_once '../../../database/index.php';
$currentPage = 'produtos';

// Busca todas as marcas para preencher os <select>
$marcasResult = mysqli_query($conexao, "SELECT id, nome FROM marcas ORDER BY nome ASC");
$marcas = mysqli_fetch_all($marcasResult, MYSQLI_ASSOC);

// Busca todas as categorias para preencher os <select>
$categoriasResult = mysqli_query($conexao, "SELECT id, nome FROM categorias ORDER BY nome ASC");
$categorias = mysqli_fetch_all($categoriasResult, MYSQLI_ASSOC);

// Paginação
$itensPorPagina = 5;
$totalProdutosSql = "SELECT COUNT(id) AS total FROM produtos";
$totalResult = mysqli_query($conexao, $totalProdutosSql);
$totalProdutos = mysqli_fetch_assoc($totalResult)['total'];
$totalPaginas = $totalProdutos > 0 ? ceil($totalProdutos / $itensPorPagina) : 1;
$paginaAtual = isset($_GET['pagina']) ? max(1, min((int)$_GET['pagina'], $totalPaginas)) : 1;
$inicio = ($paginaAtual - 1) * $itensPorPagina;

// Query com joins
$produtosPaginaSql = "
  SELECT
    p.id, p.nome, p.descricao, p.valor_custo, p.valor_venda, p.quantidade, p.genero,
    p.marca_id, p.categoria_id,
    m.nome AS marca_nome,
    c.nome AS categoria_nome
  FROM produtos p
  LEFT JOIN marcas m ON p.marca_id = m.id
  LEFT JOIN categorias c ON p.categoria_id = c.id
  ORDER BY p.nome ASC
  LIMIT ?, ?
";
$stmt = mysqli_prepare($conexao, $produtosPaginaSql);
mysqli_stmt_bind_param($stmt, "ii", $inicio, $itensPorPagina);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$produtosPagina = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Produtos - Master Control</title>
  <script src="../../scripts/theme.js"></script>
  <link rel="stylesheet" href="../../styles/styles.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[var(--color-background)] group" data-current-page="produtos">
  <div class="flex">
    <?php require_once '../../includes/components/sidebar.php'; ?>
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 z-40 lg:hidden group-[.sidebar-closed]:hidden"></div>
    <div class="flex-1 flex flex-col min-h-screen">
      <?php require_once '../../includes/components/header.php'; ?>

      <main class="p-4 md:p-8 transition-all duration-300 lg:ml-64 group-[.sidebar-closed]:lg:ml-0">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
          <h1 class="text-3xl font-bold text-[var(--color-text-primary)]">Gestão de Produtos</h1>
          <button id="open-add-modal-btn" class="bg-[var(--color-primary)] text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:opacity-90 transition-opacity flex items-center gap-2">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Adicionar Produto
          </button>
        </div>

        <div class="mb-6">
          <input type="search" id="searchInput" placeholder="Pesquisar por nome, marca ou categoria..." class="search-input w-full sm:max-w-sm p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-surface)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>

        <div class="bg-[var(--color-surface)] p-4 sm:p-6 rounded-lg shadow-md">
          <?php if (empty($produtosPagina)): ?>
            <div class="text-center py-8">
              <p class="text-[var(--color-text-secondary)]">Nenhum produto encontrado.</p>
              <p class="text-[var(--color-text-secondary)] mt-2">Clique em "Adicionar Produto" para começar.</p>
            </div>
          <?php else: ?>
            <!-- Cards Mobile -->
            <div class="grid gap-4 sm:hidden searchable-container">
              <?php foreach ($produtosPagina as $produto): ?>
                <div class="p-4 border border-[var(--color-border)] rounded-lg searchable-item">
                  <h2 class="font-semibold text-[var(--color-text-primary)]"><?= htmlspecialchars($produto['nome']); ?></h2>
                  <p class="text-sm text-[var(--color-text-secondary)] truncate"><?= htmlspecialchars($produto['descricao']); ?></p>
                  <p class="text-sm text-[var(--color-text-secondary)]">Marca: <?= htmlspecialchars($produto['marca_nome'] ?? 'N/A'); ?></p>
                  <p class="text-sm text-[var(--color-text-secondary)]">Categoria: <?= htmlspecialchars($produto['categoria_nome'] ?? 'N/A'); ?></p>
                  <div class="flex justify-between items-center mt-2">
                    <span class="font-semibold text-lg text-[var(--color-text-primary)]">R$ <?= number_format($produto['valor_venda'], 2, ',', '.'); ?></span>
                    <span class="text-sm text-[var(--color-text-secondary)]">Estoque: <?= $produto['quantidade']; ?></span>
                  </div>
                  <div class="flex gap-2 mt-4">
                    <button class="open-edit-modal-btn bg-blue-500 text-white px-3 py-1 rounded-lg text-xs hover:bg-blue-600"
                      data-id="<?= $produto['id'] ?>"
                      data-nome="<?= htmlspecialchars($produto['nome']) ?>"
                      data-descricao="<?= htmlspecialchars($produto['descricao']) ?>"
                      data-valor_custo="<?= $produto['valor_custo'] ?>"
                      data-valor_venda="<?= $produto['valor_venda'] ?>"
                      data-quantidade="<?= $produto['quantidade'] ?>"
                      data-genero="<?= $produto['genero'] ?>"
                      data-marca_id="<?= $produto['marca_id'] ?>"
                      data-categoria_id="<?= $produto['categoria_id'] ?>">Editar</button>
                    <button class="open-delete-modal-btn bg-red-500 text-white px-3 py-1 rounded-lg text-xs hover:bg-red-600"
                      data-id="<?= $produto['id'] ?>"
                      data-nome="<?= htmlspecialchars($produto['nome']) ?>">Deletar</button>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- Tabela Desktop -->
            <div class="overflow-x-auto hidden sm:block">
              <table class="w-full text-left text-sm sm:text-base min-w-[900px] searchable-table">
                <thead>
                  <tr class="border-b border-[var(--color-border)]">
                    <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Nome</th>
                    <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Descrição</th>
                    <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Valor Venda</th>
                    <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Qtd.</th>
                    <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Marca</th>
                    <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Categoria</th>
                    <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($produtosPagina as $produto): ?>
                    <tr class="border-b border-[var(--color-border)] hover:bg-[var(--color-background)] searchable-row">
                      <td class="p-3 font-medium text-[var(--color-text-primary)]"><?= htmlspecialchars($produto['nome']); ?></td>
                      <td class="p-3 text-[var(--color-text-secondary)]"><?= htmlspecialchars($produto['descricao']); ?></td>
                      <td class="p-3 text-[var(--color-text-secondary)]">R$ <?= number_format($produto['valor_venda'], 2, ',', '.'); ?></td>
                      <td class="p-3 text-[var(--color-text-secondary)]"><?= htmlspecialchars($produto['quantidade']); ?></td>
                      <td class="p-3 text-[var(--color-text-secondary)]"><?= htmlspecialchars($produto['marca_nome'] ?? 'N/A'); ?></td>
                      <td class="p-3 text-[var(--color-text-secondary)]"><?= htmlspecialchars($produto['categoria_nome'] ?? 'N/A'); ?></td>
                      <td class="p-3 flex gap-2">
                        <button class="open-edit-modal-btn bg-blue-500 text-white px-3 py-1 rounded-lg text-xs hover:bg-blue-600"
                          data-id="<?= $produto['id'] ?>"
                          data-nome="<?= htmlspecialchars($produto['nome']) ?>"
                          data-descricao="<?= htmlspecialchars($produto['descricao']) ?>"
                          data-valor_custo="<?= $produto['valor_custo'] ?>"
                          data-valor_venda="<?= $produto['valor_venda'] ?>"
                          data-quantidade="<?= $produto['quantidade'] ?>"
                          data-genero="<?= $produto['genero'] ?>"
                          data-marca_id="<?= $produto['marca_id'] ?>"
                          data-categoria_id="<?= $produto['categoria_id'] ?>">Editar</button>
                        <button class="open-delete-modal-btn bg-red-500 text-white px-3 py-1 rounded-lg text-xs hover:bg-red-600"
                          data-id="<?= $produto['id'] ?>"
                          data-nome="<?= htmlspecialchars($produto['nome']) ?>">Deletar</button>
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

  <?php
  require_once '../../includes/components/modal_add_produto.php';
  require_once '../../includes/components/modal_edit_produto.php';
  require_once '../../includes/components/modal_delete_confirm.php';
  ?>

  <script src="../../scripts/dashboard.js"></script>
</body>

</html>