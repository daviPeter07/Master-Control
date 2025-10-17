<aside id="sidebar"
  class="fixed top-0 left-0 h-full w-64 bg-[var(--color-surface)] shadow-lg 
         transform transition-transform duration-300 z-50 flex flex-col 
         group-[.sidebar-closed]:-translate-x-full">

  <!-- Logo -->
  <div class="flex items-center justify-between p-4 border-b border-[var(--color-border)]">
    <a href="/src/pages/dashboard/" class="flex items-center space-x-2 overflow-hidden">
      <?php include_once('../../../assets/icons/logo.php') ?>
      <span class="text-xl font-bold text-[var(--color-text-primary)] truncate max-w-[140px] sm:max-w-full">
        MasterControl
      </span>
    </a>
  </div>

  <!-- Navegação -->
  <nav class="flex-1 overflow-y-auto p-2 space-y-1 text-sm sm:text-base">
    <a href="/src/pages/dashboard/"
      class="flex items-center gap-2 py-2 px-2 rounded-md transition-colors font-medium
              <?php echo (isset($currentPage) && $currentPage === 'inicio')
                ? 'bg-[var(--color-primary)] text-white font-semibold'
                : 'text-[var(--color-text-secondary)] hover:bg-[var(--color-primary)] hover:text-white'; ?>">
      <span class="truncate">Início</span>
    </a>

    <a href="/src/pages/dashboard/produtos.php"
      class="flex items-center gap-2 py-2 px-2 rounded-md transition-colors font-medium
              <?php echo (isset($currentPage) && $currentPage === 'produtos')
                ? 'bg-[var(--color-primary)] text-white font-semibold'
                : 'text-[var(--color-text-secondary)] hover:bg-[var(--color-primary)] hover:text-white'; ?>">
      <span class="truncate">Produtos</span>
    </a>

    <a href="/src/pages/dashboard/clientes.php"
      class="flex items-center gap-2 py-2 px-2 rounded-md transition-colors font-medium
              <?php echo (isset($currentPage) && $currentPage === 'clientes')
                ? 'bg-[var(--color-primary)] text-white font-semibold'
                : 'text-[var(--color-text-secondary)] hover:bg-[var(--color-primary)] hover:text-white'; ?>">
      <span class="truncate">Clientes</span>
    </a>

    <a href="/src/pages/dashboard/contas.php"
      class="flex items-center gap-2 py-2 px-2 rounded-md transition-colors font-medium
              <?php echo (isset($currentPage) && $currentPage === 'contas')
                ? 'bg-[var(--color-primary)] text-white font-semibold'
                : 'text-[var(--color-text-secondary)] hover:bg-[var(--color-primary)] hover:text-white'; ?>">
      <span class="truncate">Contas</span>
    </a>

    <a href="/src/pages/dashboard/vendas.php"
      class="flex items-center gap-2 py-2 px-2 rounded-md transition-colors font-medium
              <?php echo (isset($currentPage) && $currentPage === 'vendas')
                ? 'bg-[var(--color-primary)] text-white font-semibold'
                : 'text-[var(--color-text-secondary)] hover:bg-[var(--color-primary)] hover:text-white'; ?>">
      <span class="truncate">Vendas</span>
    </a>
  </nav>

  <!-- Logout -->
  <div class="p-3 border-t border-[var(--color-border)]">
    <a href="/src/actions/auth/logout.php"
      class="flex items-center gap-2 p-2 rounded-md text-[var(--color-text-secondary)] hover:bg-red-500 hover:text-white transition-colors">
      <svg class="h-5 w-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
        <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
      </svg>
      <span class="truncate">Sair</span>
    </a>
  </div>
</aside>