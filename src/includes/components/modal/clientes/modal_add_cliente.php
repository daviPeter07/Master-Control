<div id="add-client-modal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
  <div class="bg-[var(--color-surface)] p-6 rounded-lg shadow-xl w-full max-w-md m-4">

    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-bold text-[var(--color-text-primary)]">Adicionar Novo Cliente</h2>
      <button id="close-modal-btn" class="text-[var(--color-text-secondary)] hover:text-[var(--color-text-primary)]">
        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <form action="/masterControl/src/actions/clientes/edit_cliente_action.php" method="POST">
      <div class="space-y-4">
        <div>
          <label for="nome" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Nome Completo</label>
          <input type="text" name="nome" id="nome" required placeholder="Nome completo" class="name-input w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>

        <div>
          <label for="tipo_cliente" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Tipo de Cliente</label>
          <select name="tipo_cliente" id="tipo_cliente" required class="w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
            <option value="CONSUMIDOR">Consumidor</option>
            <option value="REVENDEDORA">Revendedora</option>
          </select>
        </div>

        <div>
          <label for="telefone" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Telefone (Opcional)</label>
          <input type="tel" name="telefone" id="telefone" placeholder="Telefone (opcional)" class="phone-input w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>

        <div class="flex justify-end gap-4 pt-2">
          <button type="button" id="cancel-modal-btn" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">Cancelar</button>
          <button type="submit" class="px-4 py-2 bg-[var(--color-primary)] text-white rounded-lg hover:opacity-90">Salvar Cliente</button>
        </div>
      </div>
    </form>
  </div>
</div>