<header class="sticky top-0 z-20 bg-[var(--color-surface)]/70 backdrop-blur-sm shadow-sm transition-all duration-300 lg:ml-64 group-[.sidebar-closed]:lg:ml-0">
  <div class="px-4 sm:px-6 lg:px-8 flex flex-wrap justify-between items-center h-auto min-h-16 gap-2">

    <!-- Botão Hamburguer -->
    <button id="hamburger-button" class="p-2 rounded-md text-[var(--color-text-primary)] hover:bg-[var(--color-primary)] hover:text-white">
      <svg class="w-6 h-6 hidden group-[.sidebar-closed]:block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
      </svg>
      <svg class="w-6 h-6 block group-[.sidebar-closed]:hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15m0 0l6.75-6.75M4.5 12l6.75 6.75" />
      </svg>
    </button>

    <!-- Usuário, Theme Switcher e Logout -->
    <div class="flex-1 flex justify-end items-center gap-3">

      <!-- Theme Switcher Customizado -->
      <div class="relative">
        <button id="theme-button" class="w-8 h-8 rounded-full border-2 border-[var(--color-border)] flex items-center justify-center focus:outline-none">
          <span class="w-4 h-4 rounded-full bg-blue-400"></span>
        </button>
        <div id="theme-options" class="absolute right-0 mt-2 hidden bg-[var(--color-surface)] border border-[var(--color-border)] rounded-md shadow-md flex flex-col gap-1 p-2 z-50">
          <button class="w-6 h-6 rounded-full bg-blue-400" data-theme="light-blue"></button>
          <button class="w-6 h-6 rounded-full bg-pink-400" data-theme="light-pink"></button>
          <button class="w-6 h-6 rounded-full bg-orange-400" data-theme="light-orange"></button>
          <button class="w-6 h-6 rounded-full bg-blue-800" data-theme="dark-blue"></button>
          <button class="w-6 h-6 rounded-full bg-pink-800" data-theme="dark-pink"></button>
          <button class="w-6 h-6 rounded-full bg-orange-800" data-theme="dark-orange"></button>
        </div>
      </div>

      <!-- só aparece em md ou mais-->
      <span class="text-sm text-[var(--color-text-secondary)] hidden md:block whitespace-nowrap">
        Olá, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
      </span>

      <!-- Botão Sair -->
      <a href="../../actions/logout.php" class="text-red-500 hover:underline text-sm font-semibold whitespace-nowrap">
        Sair
      </a>


    </div>
  </div>
</header>