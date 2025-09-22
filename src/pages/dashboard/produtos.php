<?php
require_once '../../includes/auth_check.php';
$currentPage = 'produtos';

// mockdata
$produtos = [
  ['id' => 1, 'nome' => 'Base Líquida Matte', 'descricao' => 'Base de longa duração com acabamento matte.', 'valor_custo' => 25.00, 'valor_venda' => 59.90, 'quantidade' => 100, 'genero' => 'FEM', 'marca' => 'BeautyPro', 'categoria' => 'Maquiagem'],
  ['id' => 2, 'nome' => 'Hidratante Facial', 'descricao' => 'Creme hidratante com vitaminas para nutrir a pele.', 'valor_custo' => 15.00, 'valor_venda' => 49.90, 'quantidade' => 80, 'genero' => 'UNISSEX', 'marca' => 'GlowSkin', 'categoria' => 'Skincare'],
  ['id' => 3, 'nome' => 'Batom Líquido Matte', 'descricao' => 'Batom de alta pigmentação, resistente.', 'valor_custo' => 12.00, 'valor_venda' => 39.90, 'quantidade' => 150, 'genero' => 'FEM', 'marca' => 'LipLuxe', 'categoria' => 'Maquiagem'],
  ['id' => 4, 'nome' => 'Shampoo Nutritivo', 'descricao' => 'Fortalece e hidrata os fios com ingredientes naturais.', 'valor_custo' => 18.00, 'valor_venda' => 45.00, 'quantidade' => 60, 'genero' => 'UNISSEX', 'marca' => 'HairCare', 'categoria' => 'Cabelo'],
  ['id' => 5, 'nome' => 'Máscara de Argila', 'descricao' => 'Máscara purificante que controla a oleosidade.', 'valor_custo' => 10.00, 'valor_venda' => 29.90, 'quantidade' => 70, 'genero' => 'UNISSEX', 'marca' => 'SkinEssence', 'categoria' => 'Skincare'],
  ['id' => 6, 'nome' => 'Perfume Floral', 'descricao' => 'Fragrância suave e duradoura para o dia a dia.', 'valor_custo' => 45.00, 'valor_venda' => 129.90, 'quantidade' => 40, 'genero' => 'FEM', 'marca' => 'Scent & Co.', 'categoria' => 'Perfumaria'],
  ['id' => 7, 'nome' => 'Protetor Solar FPS 50', 'descricao' => 'Proteção alta contra raios UVA e UVB, toque seco.', 'valor_custo' => 22.00, 'valor_venda' => 55.00, 'quantidade' => 90, 'genero' => 'UNISSEX', 'marca' => 'SunSafe', 'categoria' => 'Skincare'],
];

$itensPorPagina = 5;
$totalProdutos = count($produtos);
$totalPaginas = ceil($totalProdutos / $itensPorPagina);
$paginaAtual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$inicio = ($paginaAtual - 1) * $itensPorPagina;
$produtosPagina = array_slice($produtos, $inicio, $itensPorPagina);
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
          <button class="bg-[var(--color-primary)] text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:opacity-90 transition-opacity flex items-center gap-2">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Adicionar Produto
          </button>
        </div>

        <div class="mb-6">
          <input type="search" placeholder="Pesquisar por nome ou categoria..." class="search-input w-full sm:max-w-sm p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-surface)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>

        <div class="bg-[var(--color-surface)] p-4 sm:p-6 rounded-lg shadow-md">

          <div class="grid gap-4 sm:hidden searchable-container">
            <?php foreach ($produtosPagina as $produto): ?>
              <div class="p-4 border border-[var(--color-border)] rounded-lg searchable-item">
                <h2 class="font-semibold text-[var(--color-text-primary)]"><?= htmlspecialchars($produto['nome']); ?></h2>
                <p class="text-sm text-[var(--color-text-secondary)] truncate"><?= htmlspecialchars($produto['descricao']); ?></p>
                <div class="flex justify-between items-center mt-2">
                  <span class="font-semibold text-lg text-[var(--color-text-primary)]">R$ <?= number_format($produto['valor_venda'], 2, ',', '.'); ?></span>
                  <span class="text-sm text-[var(--color-text-secondary)]">Estoque: <?= $produto['quantidade']; ?></span>
                </div>
                <div class="flex gap-2 mt-4">
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

          <div class="overflow-x-auto hidden sm:block">
            <table class="w-full text-left text-sm sm:text-base min-w-[900px] searchable-table">
              <thead>
                <tr class="border-b border-[var(--color-border)]">
                  <th class="p-3 font-semibold text-[var(--color-text-secondary)]">Nome</th>
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
                    <td class="p-3 text-[var(--color-text-secondary)]">R$ <?= number_format($produto['valor_venda'], 2, ',', '.'); ?></td>
                    <td class="p-3 text-[var(--color-text-secondary)]"><?= htmlspecialchars($produto['quantidade']); ?></td>
                    <td class="p-3 text-[var(--color-text-secondary)]"><?= htmlspecialchars($produto['marca']); ?></td>
                    <td class="p-3 text-[var(--color-text-secondary)]"><?= htmlspecialchars($produto['categoria']); ?></td>
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