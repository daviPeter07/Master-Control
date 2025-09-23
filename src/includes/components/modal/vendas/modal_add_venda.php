<div id="add-venda-modal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
  <div class="bg-[var(--color-surface)] p-6 rounded-lg shadow-xl w-full max-w-3xl m-4 max-h-[90vh] flex flex-col">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-bold text-[var(--color-text-primary)]">Registrar Nova Venda</h2>
      <button class="close-add-modal-btn text-[var(--color-text-secondary)]">X</button>
    </div>
    <form action="/masterControl/src/actions/vendas/add_venda_action.php" method="POST" class="flex-grow overflow-y-auto">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div>
          <label for="cliente_id" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Cliente</label>
          <select name="cliente_id" required class="w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
            <?php foreach ($clientes as $cliente): ?>
              <option value="<?= $cliente['id'] ?>"><?= htmlspecialchars($cliente['nome']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label for="metodo_pagamento" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Método de Pagamento</label>
          <select name="metodo_pagamento" required class="w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
            <option value="PIX">PIX</option>
            <option value="CARTAO_CREDITO">Cartão de Crédito</option>
            <option value="CARTAO_DEBITO">Cartão de Débito</option>
            <option value="DINHEIRO">Dinheiro</option>
            <option value="BOLETO">Boleto</option>
          </select>
        </div>
        <div>
          <label for="status_pagamento" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Status do Pagamento</label>
          <select name="status_pagamento" required class="w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
            <option value="PAGO">Pago</option>
            <option value="PENDENTE">Pendente</option>
          </select>
        </div>
      </div>

      <hr class="border-[var(--color-border)] my-4">

      <h3 class="text-lg font-semibold mb-2 text-[var(--color-text-primary)]">Itens da Venda</h3>
      <div id="itens-venda-container" class="space-y-2">
      </div>
      <button type="button" id="add-item-btn" class="mt-2 text-sm text-[var(--color-primary)] hover:underline">+ Adicionar Produto</button>

      <div class="flex justify-end gap-4 mt-6">
        <button type="button" class="cancel-add-modal-btn px-4 py-2 bg-gray-300 text-gray-800 rounded-lg">Cancelar</button>
        <button type="submit" class="px-4 py-2 bg-[var(--color-primary)] text-white rounded-lg">Salvar Venda</button>
      </div>
    </form>
  </div>
</div>

<div id="item-venda-template" class="hidden">
  <div class="flex items-center gap-2 item-venda-row">
    <select name="produtos[]" class="produto-select w-1/2 p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
      <option value="">Selecione um produto</option>
      <?php foreach ($produtos as $produto): ?>
        <option value="<?= $produto['id'] ?>" data-preco="<?= $produto['valor_venda'] ?>"><?= htmlspecialchars($produto['nome']) ?></option>
      <?php endforeach; ?>
    </select>
    <input type="number" name="quantidades[]" value="1" min="1" class="quantidade-input w-1/4 p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
    <button type="button" class="remove-item-btn text-red-500">Remover</button>
  </div>
</div>