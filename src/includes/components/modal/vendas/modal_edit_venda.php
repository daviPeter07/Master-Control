<div id="edit-venda-modal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
  <div class="bg-[var(--color-surface)] p-6 rounded-lg shadow-xl w-full max-w-3xl m-4 max-h-[90vh] flex flex-col">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-bold text-[var(--color-text-primary)]">Editar Venda</h2>
      <button class="close-edit-modal-btn text-[var(--color-text-secondary)]">X</button>
    </div>
    <form action="/masterControl/src/actions/vendas/edit_venda_action.php" method="POST" class="flex-grow overflow-y-auto">
      <input type="hidden" name="id" id="edit-venda-id">

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
      </div>

      <hr class="border-[var(--color-border)] my-4">

      <h3 class="text-lg font-semibold mb-2 text-[var(--color-text-primary)]">Itens da Venda</h3>
      <div id="edit-itens-venda-container" class="space-y-2">
      </div>
      <button type="button" id="edit-add-item-btn" class="mt-2 text-sm text-[var(--color-primary)] hover:underline">+ Adicionar Produto</button>

      <div class="flex justify-end gap-4 mt-6">
        <button type="button" class="cancel-edit-modal-btn px-4 py-2 bg-gray-300 rounded-lg">Cancelar</button>
        <button type="submit" class="px-4 py-2 bg-[var(--color-primary)] text-white rounded-lg">Salvar Alterações</button>
      </div>
    </form>
  </div>
</div>

<div id="edit-item-venda-template" class="hidden">
  <div class="flex items-center gap-2 item-venda-row">
    <select name="produtos[]" class="produto-select ...">
      <option value="">Selecione um produto</option>
      <?php foreach ($produtos as $produto): ?>
        <option value="<?= $produto['id'] ?>"><?= htmlspecialchars($produto['nome']) ?></option>
      <?php endforeach; ?>
    </select>
    <input type="number" name="quantidades[]" value="1" min="1" class="quantidade-input ...">
    <button type="button" class="remove-item-btn text-red-500">Remover</button>
  </div>
</div>