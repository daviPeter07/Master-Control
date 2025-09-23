document.addEventListener("DOMContentLoaded", () => {
  const hamburgerButton = document.getElementById("hamburger-button");
  const body = document.body;
  const overlay = document.getElementById("sidebar-overlay");
  const vendasPorDiaCtx = document.getElementById("vendasPorDiaChart");
  const vendasPorStatusCtx = document.getElementById("vendasPorStatusChart");

  //Gráficos
  if (vendasPorDiaCtx) {
    new Chart(vendasPorDiaCtx, {
      type: "bar",
      data: {
        labels: vendasPorDiaLabels,
        datasets: [
          {
            label: "Vendas (R$)",
            data: vendasPorDiaData,
            backgroundColor: "rgba(59, 130, 246, 0.5)",
            borderColor: "rgba(59, 130, 246, 1)",
            borderWidth: 1,
          },
        ],
      },
      options: { responsive: true, scales: { y: { beginAtZero: true } } },
    });
  }

  if (vendasPorStatusCtx) {
    new Chart(vendasPorStatusCtx, {
      type: "doughnut",
      data: {
        labels: ["Pago", "Pendente"],
        datasets: [
          {
            label: "Status de Vendas",
            data: vendasPorStatusData,
            backgroundColor: [
              "rgba(16, 185, 129, 0.7)",
              "rgba(245, 158, 11, 0.7)",
            ],
            borderColor: ["rgba(16, 185, 129, 1)", "rgba(245, 158, 11, 1)"],
            borderWidth: 1,
          },
        ],
      },
      options: { responsive: true },
    });
  }

  // Sidebar
  const applySidebarState = () => {
    if (
      window.innerWidth < 1024 ||
      localStorage.getItem("sidebarState") === "closed"
    ) {
      body.classList.add("sidebar-closed");
    }
  };
  applySidebarState();

  if (hamburgerButton) {
    hamburgerButton.addEventListener("click", () => {
      body.classList.toggle("sidebar-closed");
      localStorage.setItem(
        "sidebarState",
        body.classList.contains("sidebar-closed") ? "closed" : "open"
      );
    });
  }

  if (overlay) {
    overlay.addEventListener("click", () => {
      body.classList.add("sidebar-closed");
      localStorage.setItem("sidebarState", "closed");
    });
  }

  //Theme Switcher
  const htmlElement = document.documentElement;
  const themeButton = document.getElementById("theme-button");
  const themeOptions = document.getElementById("theme-options");
  const themeBtns = themeOptions
    ? themeOptions.querySelectorAll("button[data-theme]")
    : [];
  const savedTheme = localStorage.getItem("theme") || "light-blue";
  htmlElement.setAttribute("data-theme", savedTheme);
  if (themeButton) {
    const mainSpan = themeButton.querySelector("span");
    if (mainSpan) mainSpan.className = getThemeSpanClass(savedTheme);
  }

  if (themeButton && themeOptions) {
    themeButton.addEventListener("click", () =>
      themeOptions.classList.toggle("hidden")
    );
  }

  themeBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      const selectedTheme = btn.dataset.theme;
      htmlElement.setAttribute("data-theme", selectedTheme);
      localStorage.setItem("theme", selectedTheme);
      themeOptions.classList.add("hidden");
      const mainSpan = themeButton.querySelector("span");
      if (mainSpan) mainSpan.className = getThemeSpanClass(selectedTheme);
      stylePaginationButtons();
    });
  });

  document.addEventListener("click", (e) => {
    if (!themeButton.contains(e.target) && !themeOptions.contains(e.target)) {
      themeOptions.classList.add("hidden");
    }
  });

  function getThemeSpanClass(theme) {
    if (theme.includes("blue"))
      return `w-4 h-4 rounded-full ${
        theme.startsWith("light") ? "bg-blue-400" : "bg-blue-800"
      }`;
    if (theme.includes("pink"))
      return `w-4 h-4 rounded-full ${
        theme.startsWith("light") ? "bg-pink-400" : "bg-pink-800"
      }`;
    if (theme.includes("orange"))
      return `w-4 h-4 rounded-full ${
        theme.startsWith("light") ? "bg-orange-400" : "bg-orange-800"
      }`;
    return "w-4 h-4 rounded-full bg-blue-400";
  }

  //Utilitário para remover acentos
  const normalizeText = (text) =>
    text
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .toLowerCase();

  // Pesquisa Global Universal
  const searchInput = document.querySelector(".search-input");
  if (searchInput) {
    searchInput.addEventListener("input", () => {
      const termo = normalizeText(searchInput.value);

      // Seleciona qualquer tabela ou grid
      const tables = Array.from(document.querySelectorAll("table tbody tr"));
      const grids = Array.from(document.querySelectorAll(".grid > div"));
      const items = [...tables, ...grids];

      items.forEach((item) => {
        const nome = normalizeText(
          item.querySelector("td:first-child, h2")?.textContent || ""
        );
        const descricao = normalizeText(
          item.querySelector("td:nth-child(2), p")?.textContent || ""
        );

        const matchSearch = nome.includes(termo) || descricao.includes(termo);
        item.style.display = matchSearch ? "" : "none";
      });
    });
  }

  // Toggle Gráficos / KPIs com Fade
  if (body.dataset.currentPage === "inicio") {
    const chartsSection = document.getElementById("charts-section");
    const kpisSection = document.getElementById("kpis-section");
    const showChartsBtn = document.getElementById("show-charts");
    const showKpisBtn = document.getElementById("show-kpis");

    const savedView = localStorage.getItem("dashboardView") || "charts";

    const fadeIn = (el) => {
      el.classList.remove("hidden");
      el.classList.remove("opacity-0");
      el.classList.add("opacity-100");
    };

    const fadeOut = (el) => {
      el.classList.remove("opacity-100");
      el.classList.add("opacity-0");
      setTimeout(() => el.classList.add("hidden"), 300);
    };

    const setView = (view) => {
      if (view === "charts") {
        fadeOut(kpisSection);
        fadeIn(chartsSection);
        showChartsBtn.classList.add("bg-blue-500", "text-white");
        showChartsBtn.classList.remove("bg-gray-300", "text-gray-800");
        showKpisBtn.classList.add("bg-gray-300", "text-gray-800");
        showKpisBtn.classList.remove("bg-blue-500", "text-white");
      } else {
        fadeOut(chartsSection);
        fadeIn(kpisSection);
        showKpisBtn.classList.add("bg-blue-500", "text-white");
        showKpisBtn.classList.remove("bg-gray-300", "text-gray-800");
        showChartsBtn.classList.add("bg-gray-300", "text-gray-800");
        showChartsBtn.classList.remove("bg-blue-500", "text-white");
      }
      localStorage.setItem("dashboardView", view);
    };

    [chartsSection, kpisSection].forEach((sec) => {
      sec.classList.add("transition-opacity", "duration-300");
      sec.classList.contains("hidden")
        ? sec.classList.add("opacity-0")
        : sec.classList.add("opacity-100");
    });

    setView(savedView);

    showChartsBtn.addEventListener("click", () => setView("charts"));
    showKpisBtn.addEventListener("click", () => setView("kpis"));
  }

  // Paginação
  const stylePaginationButtons = () => {
    const prevBtn = document.querySelector(".pag-prev");
    const nextBtn = document.querySelector(".pag-next");

    [prevBtn, nextBtn].forEach((btn) => {
      if (!btn) return;
      btn.classList.add(
        "px-4",
        "py-2",
        "rounded",
        "bg-[var(--color-primary)]",
        "text-white",
        "hover:bg-blue-600"
      );
      if (btn.classList.contains("disabled")) {
        btn.classList.add("opacity-50", "cursor-not-allowed");
      }
    });
  };

  stylePaginationButtons();

  // Função genérica para abrir/fechar qualquer modal
  const setupModal = (modalId, openSelector, closeSelector) => {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    const openBtns = document.querySelectorAll(openSelector);
    const closeBtns = modal.querySelectorAll(closeSelector);

    const open = () => {
      modal.classList.remove("hidden");
      modal.classList.add("flex");
    };
    const close = () => {
      modal.classList.add("hidden");
      modal.classList.remove("flex");
    };

    openBtns.forEach((btn) => btn.addEventListener("click", open));
    closeBtns.forEach((btn) => btn.addEventListener("click", close));
    modal.addEventListener("click", (e) => {
      if (e.target === modal) close();
    });
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") close();
    });
  };

  // Lógica Específica para a Página de Clientes
  if (body.dataset.currentPage === "clientes") {
    // Configura os 3 modais da página
    setupModal(
      "add-client-modal",
      "#open-modal-btn",
      "#close-modal-btn, #cancel-modal-btn"
    );
    setupModal(
      "edit-client-modal",
      ".open-edit-modal-btn",
      ".close-edit-modal-btn, .cancel-edit-modal-btn"
    );
    setupModal(
      "delete-confirm-modal",
      ".open-delete-modal-btn",
      "#cancel-delete-btn"
    );

    // Lógica para preencher o modal de EDIÇÃO com dados do cliente
    document.querySelectorAll(".open-edit-modal-btn").forEach((button) => {
      button.addEventListener("click", (e) => {
        const modal = document.getElementById("edit-client-modal");
        const data = e.currentTarget.dataset;

        // Preenche os campos do formulário de edição
        modal.querySelector("#edit-id").value = data.id;
        modal.querySelector("#edit-nome").value = data.nome;
        modal.querySelector("#edit-tipo_cliente").value = data.tipo;
        modal.querySelector("#edit-telefone").value = data.telefone;
      });
    });

    // Lógica para configurar o modal de EXCLUSÃO
    document.querySelectorAll(".open-delete-modal-btn").forEach((button) => {
      button.addEventListener("click", (e) => {
        const modal = document.getElementById("delete-confirm-modal");
        const data = e.currentTarget.dataset;

        // Personaliza o texto e o link de confirmação
        modal.querySelector(
          "#delete-confirm-text"
        ).textContent = `Você tem certeza que deseja excluir o cliente "${data.nome}"?`;
        modal.querySelector(
          "#confirm-delete-btn"
        ).href = `../../actions/clintes/delete_cliente_action.php?id=${data.id}`;
      });
    });
  }

  // Lógica Específica para a Página de Produtos
  if (body.dataset.currentPage === "produtos") {
    // Configura os 3 modais da página
    setupModal(
      "add-product-modal",
      "#open-add-modal-btn",
      ".close-add-modal-btn, .cancel-add-modal-btn"
    );
    setupModal(
      "edit-product-modal",
      ".open-edit-modal-btn",
      ".close-edit-modal-btn, .cancel-edit-modal-btn"
    );
    setupModal(
      "delete-confirm-modal",
      ".open-delete-modal-btn",
      "#cancel-delete-btn"
    );

    // Lógica para preencher o modal de EDIÇÃO com dados do produto
    document.querySelectorAll(".open-edit-modal-btn").forEach((button) => {
      button.addEventListener("click", (e) => {
        const modal = document.getElementById("edit-product-modal");
        const data = e.currentTarget.dataset;

        modal.querySelector("#edit-id").value = data.id;
        modal.querySelector("#edit-nome").value = data.nome;
        modal.querySelector("#edit-descricao").value = data.descricao;
        modal.querySelector("#edit-valor_custo").value = data.valor_custo;
        modal.querySelector("#edit-valor_venda").value = data.valor_venda;
        modal.querySelector("#edit-quantidade").value = data.quantidade;
        modal.querySelector("#edit-genero").value = data.genero;
        modal.querySelector("#edit-marca_id").value = data.marca_id;
        modal.querySelector("#edit-categoria_id").value = data.categoria_id;
      });
    });

    document.querySelectorAll(".open-delete-modal-btn").forEach((button) => {
      button.addEventListener("click", (e) => {
        const modal = document.getElementById("delete-confirm-modal");
        const data = e.currentTarget.dataset;

        // Texto de confirmação
        modal.querySelector(
          "#delete-confirm-text"
        ).textContent = `Você tem certeza que deseja excluir o produto "${data.nome}"?`;

        modal.querySelector(
          "#confirm-delete-btn"
        ).href = `../../actions/produtos/delete_produto_action.php?id=${data.id}`;
      });
    });
  }
});
