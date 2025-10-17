<div id="edit-product-modal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
  <div class="bg-[var(--color-surface)] p-6 rounded-lg shadow-xl w-full max-w-2xl m-4 max-h-[90vh] overflow-y-auto">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-bold text-[var(--color-text-primary)]">Editar Produto</h2>
      <button class="close-edit-modal-btn text-[var(--color-text-secondary)]">X</button>
    </div>
    <form action="/masterControl/src/actions/produtos/edit_produto_action.php" method="POST">
      <input type="hidden" name="id" id="edit-id">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label for="edit-nome" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Nome</label>
          <input type="text" name="nome" id="edit-nome" required placeholder="Nome do produto" class="name-input w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>
        <div>
          <label for="edit-descricao" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Descrição</label>
          <textarea name="descricao" id="edit-descricao" rows="3" placeholder="Descrição do produto" class="w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]"></textarea>
        </div>
        <div>
          <label for="edit-valor_custo" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Valor de Custo (R$)</label>
          <input type="text" name="valor_custo" id="edit-valor_custo" required placeholder="R$ 0,00" class="currency-input w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>
        <div>
          <label for="edit-valor_venda" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Valor de Venda (R$)</label>
          <input type="text" name="valor_venda" id="edit-valor_venda" required placeholder="R$ 0,00" class="currency-input w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>
        <div>
          <label for="edit-quantidade" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Quantidade em Estoque</label>
          <input type="text" name="quantidade" id="edit-quantidade" required placeholder="0" class="number-input w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>
        <div>
          <label for="edit-genero" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Gênero</label>
          <select name="genero" id="edit-genero" required class="w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
            <option value="UNISSEX">Unissex</option>
            <option value="FEM">Feminino</option>
            <option value="MASC">Masculino</option>
          </select>
        </div>
        <div>
          <label for="edit-marca_id" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Marca</label>
          <select name="marca_id" id="edit-marca_id" class="w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
            <option value="">Nenhuma</option>
            <?php foreach ($marcas as $marca): ?>
              <option value="<?= $marca['id'] ?>"><?= htmlspecialchars($marca['nome']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label for="edit-categoria_id" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Categoria</label>
          <select name="categoria_id" id="edit-categoria_id" class="w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
            <option value="">Nenhuma</option>
            <?php foreach ($categorias as $categoria): ?>
              <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nome']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="flex justify-end gap-4 mt-6">
        <button type="button" class="cancel-edit-modal-btn px-4 py-2 bg-gray-300 text-gray-800 rounded-lg">Cancelar</button>
        <button type="submit" class="px-4 py-2 bg-[var(--color-primary)] text-white rounded-lg">Salvar Alterações</button>
      </div>
    </form>
  </div>
</div>