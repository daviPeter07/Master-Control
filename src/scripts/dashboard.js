document.addEventListener("DOMContentLoaded", () => {
  const hamburgerButton = document.getElementById("hamburger-button");
  const body = document.body;
  const overlay = document.getElementById("sidebar-overlay");
  const vendasPorDiaCtx = document.getElementById("vendasPorDiaChart");
  const vendasPorStatusCtx = document.getElementById("vendasPorStatusChart");

  // ======== Gráfico de Barras: Vendas por Dia ========
  if (vendasPorDiaCtx) {
    new Chart(vendasPorDiaCtx, {
      type: "bar",
      data: {
        labels: ["15/09", "16/09", "17/09", "18/09", "19/09", "20/09", "21/09"],
        datasets: [
          {
            label: "Vendas (R$)",
            data: [120, 190, 300, 500, 200, 350, 450],
            backgroundColor: "rgba(59, 130, 246, 0.5)",
            borderColor: "rgba(59, 130, 246, 1)",
            borderWidth: 1,
          },
        ],
      },
      options: {
        responsive: true,
        scales: { y: { beginAtZero: true } },
      },
    });
  }

  // ======== Gráfico de Rosca: Vendas por Status ========
  if (vendasPorStatusCtx) {
    new Chart(vendasPorStatusCtx, {
      type: "doughnut",
      data: {
        labels: ["Pago", "Pendente"],
        datasets: [
          {
            label: "Status de Vendas",
            data: [3, 2],
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

  // ======== Sidebar Toggle ========
  const applySidebarState = () => {
    if (window.innerWidth < 1024) {
      body.classList.add("sidebar-closed");
    } else if (localStorage.getItem("sidebarState") === "closed") {
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

  // ======== Theme Switcher Customizado ========
  const htmlElement = document.documentElement;
  const themeButton = document.getElementById("theme-button");
  const themeOptions = document.getElementById("theme-options");
  const themeBtns = themeOptions
    ? themeOptions.querySelectorAll("button[data-theme]")
    : [];

  // Aplica tema salvo
  const savedTheme = localStorage.getItem("theme") || "light-blue";
  htmlElement.setAttribute("data-theme", savedTheme);
  if (themeButton) {
    const mainSpan = themeButton.querySelector("span");
    if (mainSpan) mainSpan.className = getThemeSpanClass(savedTheme);
  }

  // Toggle dropdown
  if (themeButton && themeOptions) {
    themeButton.addEventListener("click", () => {
      themeOptions.classList.toggle("hidden");
    });
  }

  // Seleção de tema
  themeBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      const selectedTheme = btn.dataset.theme;
      htmlElement.setAttribute("data-theme", selectedTheme);
      localStorage.setItem("theme", selectedTheme);
      themeOptions.classList.add("hidden");

      // Atualiza bolinha do botão principal
      const mainSpan = themeButton.querySelector("span");
      if (mainSpan) mainSpan.className = getThemeSpanClass(selectedTheme);
    });
  });

  // Fecha dropdown clicando fora
  document.addEventListener("click", (e) => {
    if (!themeButton.contains(e.target) && !themeOptions.contains(e.target)) {
      themeOptions.classList.add("hidden");
    }
  });

  // ======== Função utilitária para cores da bolinha principal ========
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
});
