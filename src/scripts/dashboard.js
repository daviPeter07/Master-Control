document.addEventListener("DOMContentLoaded", () => {
  const hamburgerButton = document.getElementById("hamburger-button");
  const body = document.body;
  const overlay = document.getElementById("sidebar-overlay");
  const vendasPorDiaCtx = document.getElementById("vendasPorDiaChart");
  const vendasPorStatusCtx = document.getElementById("vendasPorStatusChart");

  // Gráfico de Barras: Vendas por Dia
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
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    });
  }

  // Gráfico de Rosca (Doughnut): Vendas por Status
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
      options: {
        responsive: true,
      },
    });
  }

  const applySidebarState = () => {
    // Para telas pequenas, sempre comece fechado. Para telas grandes, use o estado salvo.
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
      // Salva a preferência do usuário
      if (body.classList.contains("sidebar-closed")) {
        localStorage.setItem("sidebarState", "closed");
      } else {
        localStorage.setItem("sidebarState", "open");
      }
    });
  }

  if (overlay) {
    overlay.addEventListener("click", () => {
      body.classList.add("sidebar-closed");
      localStorage.setItem("sidebarState", "closed");
    });
  }

  const themeSwitcher = document.getElementById("theme-switcher");
  const htmlElement = document.documentElement;

  const applyTheme = () => {
    const savedTheme = localStorage.getItem("theme") || "light-blue";
    htmlElement.setAttribute("data-theme", savedTheme);
    if (themeSwitcher) {
      themeSwitcher.value = savedTheme;
    }
  };
  applyTheme();

  if (themeSwitcher) {
    themeSwitcher.addEventListener("change", () => {
      const selectedTheme = themeSwitcher.value;
      htmlElement.setAttribute("data-theme", selectedTheme);
      localStorage.setItem("theme", selectedTheme);
    });
  }
});
