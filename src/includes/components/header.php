<header class="sticky top-0 z-20 bg-[var(--color-surface)]/70 backdrop-blur-sm shadow-sm transition-all duration-300 lg:ml-64 group-[.sidebar-closed]:lg:ml-0">

  <div class="px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
    <button id="hamburger-button" class="p-2 rounded-md text-[var(--color-text-primary)] hover:bg-[var(--color-primary)] hover:text-white">

      <svg class="w-6 h-6 hidden group-[.sidebar-closed]:block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
      </svg>

      <svg class="w-6 h-6 block group-[.sidebar-closed]:hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15m0 0l6.75-6.75M4.5 12l6.75 6.75" />
      </svg>

    </button>

    <div class="flex items-center space-x-4">
      <select id="theme-switcher" class="block w-full bg-[var(--color-surface)] border border-[var(--color-border)] text-[var(--color-text-secondary)] rounded-md shadow-sm py-1 pl-2 pr-8 text-sm focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        <option value="light-blue">Claro - Azul</option>
        <option value="light-pink">Claro - Rosa</option>
        <option value="light-orange">Claro - Laranja</option>
        <option value="dark-blue">Escuro - Azul</option>
        <option value="dark-pink">Escuro - Rosa</option>
        <option value="dark-orange">Escuro - Laranja</option>
      </select>

      <span class="text-sm text-[var(--color-text-secondary)] hidden sm:block whitespace-nowrap">
        Olá, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
      </span>
      <a href="../../actions/logout.php" class="text-red-500 hover:underline text-sm font-semibold">Sair</a>
    </div>
  </div>
</header>