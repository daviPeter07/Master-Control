<?php
require_once '../../includes/auth_check.php';
$currentPage = 'contas';

// mockdata clientes e usuários
$clientes = [
  1 => 'João Silva',
  2 => 'Maria Souza',
  3 => 'Carlos Lima',
];

$usuarios = [
  1 => 'Admin',
  2 => 'Vendedor 1',
  3 => 'Vendedor 2',
];

// mockdata contas a receber
$contas = [
  ['id' => 1, 'cliente_id' => 1, 'usuario_id' => 2, 'valor_total' => 150.00, 'metodo_pagamento' => 'Cartão', 'status_pagamento' => 'Pago', 'data_venda' => '2025-09-01'],
  ['id' => 2, 'cliente_id' => 2, 'usuario_id' => 1, 'valor_total' => 300.00, 'metodo_pagamento' => 'Boleto', 'status_pagamento' => 'Pendente', 'data_venda' => '2025-09-05'],
  ['id' => 3, 'cliente_id' => 3, 'usuario_id' => 3, 'valor_total' => 75.50, 'metodo_pagamento' => 'Pix', 'status_pagamento' => 'Pago', 'data_venda' => '2025-09-10'],
];
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light-blue">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contas a Receber - Master Control</title>
  <link rel="stylesheet" href="../../styles/styles.css">
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
          <h1 class="text-3xl font-bold text-[var(--color-text-primary)]">Contas a Receber</h1>
          <button class="bg-[var(--color-primary)] text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:opacity-90 transition-opacity flex items-center gap-2">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Adicionar Conta
          </button>
        </div>

        <div class="mb-6">
          <input type="search" placeholder="Pesquisar por cliente..."
            class="w-full sm:max-w-sm p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-surface)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>

        <div class="bg-[var(--color-surface)] p-4 sm:p-6 rounded-lg shadow-md">

          <!-- Cards mobile -->
          <div class="grid gap-4 sm:hidden">
            <?php foreach ($contas as $conta): ?>
              <div class="p-4 border border-[var(--color-border)] rounded-lg">
                <h2 class="font-semibold text-[var(--color-text-primary)]"><?php echo htmlspecialchars($clientes[$conta['cliente_id']]); ?></h2>
                <p class="text-sm text-[var(--color-text-secondary)]">Usuário: <?php echo htmlspecialchars($usuarios[$conta['usuario_id']]); ?></p>
                <p class="text-sm text-[var(--color-text-secondary)]">Valor Total: R$ <?php echo number_format($conta['valor_total'], 2, ',', '.'); ?></p>
                <p class="text-sm text-[var(--color-text-secondary)]">Método: <?php echo htmlspecialchars($conta['metodo_pagamento']); ?></p>
                <p class="text-sm text-[var(--color-text-secondary)]">Status: <?php echo htmlspecialchars($conta['status_pagamento']); ?></p>
                <p class="text-sm text-[var(--color-text-secondary)]">Data da Venda: <?php echo htmlspecialchars($conta['data_venda']); ?></p>
                <div class="flex gap-4 mt-2">
                  <a href="#" class="text-blue-500 hover:underline">Editar</a>
                  <a href="#" class="text-red-500 hover:underline">Deletar</a>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <!-- Tabela desktop -->
          <div class="overflow-x-auto hidden sm:block">
            <table class="w-full text-left min-w-[700px]">
              <thead>
                <tr class="border-b border-[var(--color-border)]">
                  <th class="p-3 text-sm font-semibold text-[var(--color-text-secondary)]">Cliente</th>
                  <th class="p-3 text-sm font-semibold text-[var(--color-text-secondary)]">Usuário</th>
                  <th class="p-3 text-sm font-semibold text-[var(--color-text-secondary)]">Valor Total</th>
                  <th class="p-3 text-sm font-semibold text-[var(--color-text-secondary)]">Método</th>
                  <th class="p-3 text-sm font-semibold text-[var(--color-text-secondary)]">Status</th>
                  <th class="p-3 text-sm font-semibold text-[var(--color-text-secondary)]">Data da Venda</th>
                  <th class="p-3 text-sm font-semibold text-[var(--color-text-secondary)]">Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($contas as $conta): ?>
                  <tr class="border-b border-[var(--color-border)] hover:bg-[var(--color-background)]">
                    <td class="p-3 font-medium text-[var(--color-text-primary)]"><?php echo htmlspecialchars($clientes[$conta['cliente_id']]); ?></td>
                    <td class="p-3 text-[var(--color-text-secondary)]"><?php echo htmlspecialchars($usuarios[$conta['usuario_id']]); ?></td>
                    <td class="p-3 text-[var(--color-text-secondary)]">R$ <?php echo number_format($conta['valor_total'], 2, ',', '.'); ?></td>
                    <td class="p-3 text-[var(--color-text-secondary)]"><?php echo htmlspecialchars($conta['metodo_pagamento']); ?></td>
                    <td class="p-3 text-[var(--color-text-secondary)]"><?php echo htmlspecialchars($conta['status_pagamento']); ?></td>
                    <td class="p-3 text-[var(--color-text-secondary)]"><?php echo htmlspecialchars($conta['data_venda']); ?></td>
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