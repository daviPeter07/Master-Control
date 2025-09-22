<?php
require_once '../../includes/auth_check.php';
$currentPage = 'clientes';

// mockdata de clientes
$clientes = [
  ['id' => 1, 'nome' => 'João Silva', 'tipo_cliente' => 'Consumidor', 'telefone' => '(92) 99999-1111', 'criado_em' => '2025-09-01'],
  ['id' => 2, 'nome' => 'Maria Souza', 'tipo_cliente' => 'Revendedor', 'telefone' => '(92) 98888-2222', 'criado_em' => '2025-09-05'],
  ['id' => 3, 'nome' => 'Carlos Lima', 'tipo_cliente' => 'Consumidor', 'telefone' => '(92) 97777-3333', 'criado_em' => '2025-09-10'],
  ['id' => 4, 'nome' => 'Ana Pereira', 'tipo_cliente' => 'Revendedor', 'telefone' => '(92) 96666-4444', 'criado_em' => '2025-09-12'],
  ['id' => 5, 'nome' => 'Pedro Alves', 'tipo_cliente' => 'Consumidor', 'telefone' => '(92) 95555-5555', 'criado_em' => '2025-09-15'],
];
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light-blue">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Clientes - Master Control</title>
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
          <h1 class="text-3xl font-bold text-[var(--color-text-primary)]">Gestão de Clientes</h1>
          <button class="bg-[var(--color-primary)] text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:opacity-90 transition-opacity flex items-center gap-2">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Adicionar Cliente
          </button>
        </div>

        <div class="mb-6">
          <input type="search" placeholder="Pesquisar por nome do cliente..."
            class="w-full sm:max-w-sm p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-surface)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>

        <div class="bg-[var(--color-surface)] p-4 sm:p-6 rounded-lg shadow-md">

          <!-- Cards para mobile -->
          <div class="grid gap-4 sm:hidden">
            <?php foreach ($clientes as $cliente): ?>
              <div class="p-4 border border-[var(--color-border)] rounded-lg">
                <h2 class="font-semibold text-[var(--color-text-primary)]"><?php echo htmlspecialchars($cliente['nome']); ?></h2>
                <p class="text-sm text-[var(--color-text-secondary)]">Tipo: <?php echo htmlspecialchars($cliente['tipo_cliente']); ?></p>
                <p class="text-sm text-[var(--color-text-secondary)]">Telefone: <?php echo htmlspecialchars($cliente['telefone']); ?></p>
                <p class="text-sm text-[var(--color-text-secondary)]">Criado em: <?php echo htmlspecialchars($cliente['criado_em']); ?></p>
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
                  <th class="p-3 text-sm font-semibold text-[var(--color-text-secondary)]">Tipo de Cliente</th>
                  <th class="p-3 text-sm font-semibold text-[var(--color-text-secondary)]">Telefone</th>
                  <th class="p-3 text-sm font-semibold text-[var(--color-text-secondary)]">Criado em</th>
                  <th class="p-3 text-sm font-semibold text-[var(--color-text-secondary)]">Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($clientes as $cliente): ?>
                  <tr class="border-b border-[var(--color-border)] hover:bg-[var(--color-background)]">
                    <td class="p-3 font-medium text-[var(--color-text-primary)]"><?php echo htmlspecialchars($cliente['nome']); ?></td>
                    <td class="p-3 text-[var(--color-text-secondary)]"><?php echo htmlspecialchars($cliente['tipo_cliente']); ?></td>
                    <td class="p-3 text-[var(--color-text-secondary)]"><?php echo htmlspecialchars($cliente['telefone']); ?></td>
                    <td class="p-3 text-[var(--color-text-secondary)]"><?php echo htmlspecialchars($cliente['criado_em']); ?></td>
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