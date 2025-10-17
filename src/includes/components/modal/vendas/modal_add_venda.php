<div id="add-venda-modal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
  <div class="bg-[var(--color-surface)] p-6 rounded-lg shadow-xl w-full max-w-3xl m-4 max-h-[90vh] flex flex-col">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-bold text-[var(--color-text-primary)]">Registrar Nova Venda</h2>
      <button class="close-add-modal-btn text-[var(--color-text-secondary)]">X</button>
    </div>
    <form action="/masterControl/src/actions/vendas/add_venda_action.php" method="POST" class="flex-grow overflow-y-auto">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div>
          <div class="flex items-center justify-between mb-1">
            <label for="cliente_id" class="block text-sm font-medium text-[var(--color-text-secondary)]">Cliente</label>
            <button type="button" id="quick-add-cliente-btn" class="text-xs text-[var(--color-primary)] hover:underline">+ Cadastrar Novo</button>
          </div>
          <select name="cliente_id" id="cliente-select" class="w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
            <option value="">Cliente Avulso</option>
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
  <div class="item-venda-row border border-[var(--color-border)] rounded-lg p-3 mb-2">
    <!-- Desktop Layout -->
    <div class="hidden sm:flex items-center gap-2">
      <div class="w-1/2">
        <div class="flex items-center gap-1 mb-1">
          <select name="produtos[]" class="produto-select w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
            <option value="">Selecione um produto</option>
            <option value="livre" data-preco="0">Produto Livre</option>
            <?php foreach ($produtos as $produto): ?>
              <option value="<?= $produto['id'] ?>" data-preco="<?= $produto['valor_venda'] ?>"><?= htmlspecialchars($produto['nome']) ?></option>
            <?php endforeach; ?>
          </select>
          <button type="button" class="quick-add-produto-btn text-xs text-[var(--color-primary)] hover:underline whitespace-nowrap">+ Novo</button>
        </div>
      </div>
      <input type="text" name="produtos_livres[]" placeholder="Nome do produto" class="produto-livre-input w-1/4 p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)] hidden">
      <input type="number" name="precos_livres[]" placeholder="Preço" step="0.01" min="0" class="preco-livre-input w-20 p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)] hidden">
      <input type="text" name="quantidades[]" value="1" class="number-input w-16 p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
      <button type="button" class="remove-item-btn text-red-500 hover:bg-red-50 px-2 py-1 rounded">Remover</button>
    </div>

    <!-- Mobile Layout -->
    <div class="sm:hidden space-y-2">
      <div class="flex items-center gap-1">
        <select name="produtos[]" class="produto-select flex-1 p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
          <option value="">Selecione um produto</option>
          <option value="livre" data-preco="0">Produto Livre</option>
          <?php foreach ($produtos as $produto): ?>
            <option value="<?= $produto['id'] ?>" data-preco="<?= $produto['valor_venda'] ?>"><?= htmlspecialchars($produto['nome']) ?></option>
          <?php endforeach; ?>
        </select>
        <button type="button" class="quick-add-produto-btn text-xs text-[var(--color-primary)] hover:underline px-2 py-1">+ Novo</button>
      </div>
      <div class="flex gap-2">
        <input type="text" name="produtos_livres[]" placeholder="Nome do produto" class="produto-livre-input flex-1 p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)] hidden">
        <input type="number" name="precos_livres[]" placeholder="Preço" step="0.01" min="0" class="preco-livre-input w-20 p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)] hidden">
        <input type="text" name="quantidades[]" value="1" class="number-input w-16 p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        <button type="button" class="remove-item-btn text-red-500 hover:bg-red-50 px-2 py-1 rounded text-xs">Remover</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Cadastro Rápido de Cliente -->
<div id="quick-add-cliente-modal" class="fixed inset-0 bg-black/60 items-center justify-center" style="display: none; z-index: 9999;">
  <div class="bg-[var(--color-surface)] p-6 rounded-lg shadow-xl w-full max-w-md m-4">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg font-bold text-[var(--color-text-primary)]">Cadastrar Cliente</h3>
      <button class="close-quick-cliente-modal-btn text-[var(--color-text-secondary)]">X</button>
    </div>
    <form id="quick-cliente-form">
      <div class="space-y-4">
        <div>
          <label for="quick_nome" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Nome *</label>
          <input type="text" id="quick_nome" name="nome" required placeholder="Nome completo" class="name-input w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>
        <div>
          <label for="quick_tipo_cliente" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Tipo de Cliente *</label>
          <select id="quick_tipo_cliente" name="tipo_cliente" required class="w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
            <option value="">Selecione</option>
            <option value="CONSUMIDOR">Consumidor</option>
            <option value="REVENDEDORA">Revendedora</option>
          </select>
        </div>
        <div>
          <label for="quick_telefone" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Telefone</label>
          <input type="tel" id="quick_telefone" name="telefone" placeholder="Telefone (opcional)" class="phone-input w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>
      </div>
      <div class="flex justify-end gap-4 mt-6">
        <button type="button" class="cancel-quick-cliente-modal-btn px-4 py-2 bg-gray-300 text-gray-800 rounded-lg">Cancelar</button>
        <button type="submit" class="px-4 py-2 bg-[var(--color-primary)] text-white rounded-lg">Cadastrar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal de Cadastro Rápido de Produto -->
<div id="quick-add-produto-modal" class="fixed inset-0 bg-black/60 items-center justify-center" style="display: none; z-index: 9999;">
  <div class="bg-[var(--color-surface)] p-6 rounded-lg shadow-xl w-full max-w-md m-4">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-lg font-bold text-[var(--color-text-primary)]">Cadastrar Produto</h3>
      <button class="close-quick-produto-modal-btn text-[var(--color-text-secondary)]">X</button>
    </div>
    <form id="quick-produto-form">
      <div class="space-y-4">
        <div>
          <label for="quick_produto_nome" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Nome *</label>
          <input type="text" id="quick_produto_nome" name="nome" required placeholder="Nome do produto" class="name-input w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>
        <div>
          <label for="quick_produto_descricao" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Descrição</label>
          <input type="text" id="quick_produto_descricao" name="descricao" placeholder="Descrição do produto" class="w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>
        <div>
          <label for="quick_produto_valor_venda" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Valor de Venda *</label>
          <input type="text" id="quick_produto_valor_venda" name="valor_venda" required placeholder="R$ 0,00" class="currency-input w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>
        <div>
          <label for="quick_produto_quantidade" class="block text-sm font-medium text-[var(--color-text-secondary)] mb-1">Quantidade em Estoque</label>
          <input type="text" id="quick_produto_quantidade" name="quantidade" value="0" placeholder="0" class="number-input w-full p-2 border border-[var(--color-border)] rounded-lg bg-[var(--color-background)] text-[var(--color-text-primary)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
        </div>
      </div>
      <div class="flex justify-end gap-4 mt-6">
        <button type="button" class="cancel-quick-produto-modal-btn px-4 py-2 bg-gray-300 text-gray-800 rounded-lg">Cancelar</button>
        <button type="submit" class="px-4 py-2 bg-[var(--color-primary)] text-white rounded-lg">Cadastrar</button>
      </div>
    </form>
  </div>
</div>