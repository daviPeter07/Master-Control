<?php
require_once '../../includes/auth_check.php';
$currentPage = 'produtos';

// mockdata
$produtos = [
  ['id' => 1, 'nome' => 'Camiseta Estampada Unissex', 'valor_venda' => 79.90, 'quantidade' => 50, 'marca' => 'UrbanStyle', 'categoria' => 'Vestuário'],
  ['id' => 2, 'nome' => 'Calça Jeans Slim Fit', 'valor_venda' => 189.90, 'quantidade' => 35, 'marca' => 'Denim Co.', 'categoria' => 'Vestuário'],
  ['id' => 3, 'nome' => 'Tênis Runner Pro', 'valor_venda' => 349.90, 'quantidade' => 20, 'marca' => 'Speedster', 'categoria' => 'Calçados'],
  ['id' => 4, 'nome' => 'Boné Aba Curva', 'valor_venda' => 59.90, 'quantidade' => 80, 'marca' => 'HeadWear', 'categoria' => 'Acessórios'],
  ['id' => 5, 'nome' => 'Moletom com Capuz', 'valor_venda' => 249.90, 'quantidade' => 25, 'marca' => 'UrbanStyle', 'categoria' => 'Vestuário'],
];
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light-blue">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Produtos - Master Control</title>
  <link rel="stylesheet" href="../../styles/styles.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[var(--color-background)] group">

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
          <input type="search" placeholder="Pesquisar por nome do produto..."
            class="w-full sm:max-w-sm p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-surface)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>

        <div class="bg-[var(--color-surface)] p-4 sm:p-6 rounded-lg shadow-md">

          <!-- Cards para mobile -->
          <div class="grid gap-4 sm:hidden">
            <?php foreach ($produtos as $produto): ?>
              <div class="p-4 border border-[var(--color-border)] rounded-lg">
                <h2 class="font-semibold text-[var(--color-text-primary)]"><?php echo htmlspecialchars($produto['nome']); ?></h2>
                <p class="text-sm text-[var(--color-text-secondary)]">Valor: R$ <?php echo number_format($produto['valor_venda'], 2, ',', '.'); ?></p>
                <p class="text-sm text-[var(--color-text-secondary)]">Qtd: <?php echo htmlspecialchars($produto['quantidade']); ?></p>
                <p class="text-sm text-[var(--color-text-secondary)]">Marca: <?php echo htmlspecialchars($produto['marca']); ?></p>
                <p class="text-sm text-[var(--color-text-secondary)]">Categoria: <?php echo htmlspecialchars($produto['categoria']); ?></p>
                <div class="flex gap-4 mt-2">
                  <a href="#" class="text-blue-500 hover:underline">Editar</a>
                  <a href="#" class="text-red-500 hover:underline">Deletar</a>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <!-- Tabela para desktop -->
          <div class="overflow-x-auto hidden sm:block">
            <table class="w-full text-left min-w-[600px]">
              <thead>
                <tr class="border-b border-[var(--color-border)]">
                  <th class="p-3 text-sm font-semibold text-[var(--color-text-secondary)]">Nome</th>
                  <th class="p-3 text-sm font-semibold text-[var(--color-text-secondary)]">Valor de Venda</th>
                  <th class="p-3 text-sm font-semibold text-[var(--color-text-secondary)]">Qtd.</th>
                  <th class="p-3 text-sm font-semibold text-[var(--color-text-secondary)]">Marca</th>
                  <th class="p-3 text-sm font-semibold text-[var(--color-text-secondary)]">Categoria</th>
                  <th class="p-3 text-sm font-semibold text-[var(--color-text-secondary)]">Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($produtos as $produto): ?>
                  <tr class="border-b border-[var(--color-border)] hover:bg-[var(--color-background)]">
                    <td class="p-3 font-medium text-[var(--color-text-primary)]"><?php echo htmlspecialchars($produto['nome']); ?></td>
                    <td class="p-3 text-[var(--color-text-secondary)]">R$ <?php echo number_format($produto['valor_venda'], 2, ',', '.'); ?></td>
                    <td class="p-3 text-[var(--color-text-secondary)]"><?php echo htmlspecialchars($produto['quantidade']); ?></td>
                    <td class="p-3 text-[var(--color-text-secondary)]"><?php echo htmlspecialchars($produto['marca']); ?></td>
                    <td class="p-3 text-[var(--color-text-secondary)]"><?php echo htmlspecialchars($produto['categoria']); ?></td>
                    <td class="p-3">
                      <div class="flex gap-4">
                        <a href="#" class="text-blue-500 hover:underline">Editar</a>
                        <a href="#" class="text-red-500 hover:underline">Deletar</a>
                      </div>
                    </td>
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