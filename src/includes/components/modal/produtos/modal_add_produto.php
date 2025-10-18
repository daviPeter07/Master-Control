<div id="add-product-modal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
  <div class="bg-[var(--color-surface)] p-6 rounded-lg shadow-xl w-full max-w-2xl m-4 max-h-[90vh] overflow-y-auto">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-bold text-[var(--color-text-primary)]">Adicionar Novo Produto</h2>
      <button class="close-add-modal-btn text-[var(--color-text-secondary)]">X</button>
    </div>
    <form action="/src/actions/produtos/add_produto_action.php" method="POST">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label for="nome" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Nome</label>
          <input type="text" name="nome" required placeholder="Nome do produto" class="name-input w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>
        <div>
          <label for="descricao" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Descrição</label>
          <textarea name="descricao" rows="3" placeholder="Descrição do produto" class="w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]"></textarea>
        </div>
        <div>
          <label for="valor_custo" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Valor de Custo (R$)</label>
          <input type="text" name="valor_custo" required placeholder="R$ 0,00" class="currency-input w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>
        <div>
          <label for="valor_venda" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Valor de Venda (R$)</label>
          <input type="text" name="valor_venda" required placeholder="R$ 0,00" class="currency-input w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>
        <div>
          <label for="quantidade" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Quantidade em Estoque</label>
          <input type="text" name="quantidade" required placeholder="0" class="number-input w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>
        <div>
          <label for="genero" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Gênero</label>
          <select name="genero" required class="w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
            <option value="UNISSEX">Unissex</option>
            <option value="FEM">Feminino</option>
            <option value="MASC">Masculino</option>
          </select>
        </div>
        <div>
          <label for="marca_id" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Marca</label>
          <select name="marca_id" class="w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
            <option value="">Nenhuma</option>
            <?php if (!empty($marcas)): ?>
              <?php foreach ($marcas as $marca): ?>
                <option value="<?= $marca['id'] ?>"><?= htmlspecialchars($marca['nome']) ?></option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>
        <div>
          <label for="categoria_id" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Categoria</label>
          <select name="categoria_id" class="w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
            <option value="">Nenhuma</option>
            <?php if (!empty($categorias)): ?>
              <?php foreach ($categorias as $categoria): ?>
                <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nome']) ?></option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </div>
      </div>
      <div class="flex justify-end gap-4 mt-6">
        <button type="button" class="cancel-add-modal-btn px-4 py-2 bg-gray-300 text-gray-800 rounded-lg">Cancelar</button>
        <button type="submit" class="px-4 py-2 bg-[var(--color-primary)] text-white rounded-lg">Salvar Produto</button>
      </div>
    </form>
  </div>
</div>