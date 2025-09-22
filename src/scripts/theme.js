(function () {
  // Função para pegar o tema do localStorage ou usar um padrão
  function getInitialTheme() {
    try {
      const savedTheme = localStorage.getItem("theme");
      if (savedTheme) {
        return savedTheme;
      }
    } catch (e) {
      // localStorage pode estar desabilitado
      console.warn("LocalStorage is not available.");
    }
    return "light-blue"; // Tema padrão
  }

  // Aplica o tema na tag <html> imediatamente
  document.documentElement.setAttribute("data-theme", getInitialTheme());
})();
