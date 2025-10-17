/**
 * Sistema de Formatação Dinâmica para Inputs
 * Melhora a UX com formatações automáticas e validações inteligentes
 */

class InputFormatter {
  constructor() {
    this.init();
  }

  init() {
    this.setupPhoneFormatter();
    this.setupNameFormatter();
    this.setupCurrencyFormatter();
    this.setupNumberFormatter();
    this.setupAutoPlaceholders();
  }

  /**
   * Formatação automática de telefone brasileiro
   * Formato: (XX) 9 XXXX-XXXX ou (XX) XXXX-XXXX
   */
  setupPhoneFormatter() {
    // Remove listeners anteriores para evitar duplicação
    if (this.phoneInputHandler) {
      document.removeEventListener("input", this.phoneInputHandler);
      document.removeEventListener("keyup", this.phoneInputHandler);
    }

    // Cria novo handler
    this.phoneInputHandler = (e) => {
      if (e.target.classList.contains("phone-input")) {
        this.formatPhone(e.target);
      }
    };

    // Event delegation para elementos existentes e futuros
    document.addEventListener("input", this.phoneInputHandler);
    document.addEventListener("keyup", this.phoneInputHandler);

    document.addEventListener("focus", (e) => {
      if (e.target.classList.contains("phone-input") && !e.target.value) {
        e.target.placeholder = "+55 (XX) 9 XXXX-XXXX";
      }
    });

    document.addEventListener("blur", (e) => {
      if (e.target.classList.contains("phone-input") && !e.target.value) {
        e.target.placeholder = "Telefone (opcional)";
      }
    });

    // Aplica formatação em elementos já existentes
    setTimeout(() => {
      const phoneInputs = document.querySelectorAll(".phone-input");
      phoneInputs.forEach((input) => {
        if (input.value) {
          this.formatPhone(input);
        }
      });
    }, 100);
  }

  formatPhone(input) {
    let value = input.value.replace(/\D/g, ""); // Remove tudo que não é dígito

    // Limita a 11 dígitos (DDD + 9 dígitos) ou 10 dígitos (DDD + 8 dígitos)
    if (value.length > 11) {
      value = value.substring(0, 11);
    }

    // Formatação baseada no tamanho
    if (value.length <= 2) {
      input.value = value;
    } else if (value.length <= 6) {
      input.value = `(${value.substring(0, 2)}) ${value.substring(2)}`;
    } else if (value.length <= 10) {
      input.value = `(${value.substring(0, 2)}) ${value.substring(
        2,
        6
      )}-${value.substring(6)}`;
    } else {
      input.value = `(${value.substring(0, 2)}) ${value.substring(
        2,
        3
      )} ${value.substring(3, 7)}-${value.substring(7)}`;
    }
  }

  /**
   * Capitalização automática de nomes
   */
  setupNameFormatter() {
    document.addEventListener("input", (e) => {
      if (e.target.classList.contains("name-input")) {
        this.formatName(e.target);
      }
    });
  }

  formatName(input) {
    let value = input.value;

    // Capitaliza primeira letra de cada palavra
    value = value.replace(/\b\w/g, (char) => char.toUpperCase());

    input.value = value;
  }

  /**
   * Formatação de moeda brasileira
   */
  setupCurrencyFormatter() {
    document.addEventListener("input", (e) => {
      if (e.target.classList.contains("currency-input")) {
        this.formatCurrency(e.target);
      }
    });

    document.addEventListener("focus", (e) => {
      if (e.target.classList.contains("currency-input") && !e.target.value) {
        e.target.placeholder = "R$ 0,00";
      }
    });
  }

  formatCurrency(input) {
    let value = input.value.replace(/\D/g, ""); // Remove tudo que não é dígito

    if (value === "") {
      input.value = "";
      return;
    }

    // Converte para centavos
    let amount = parseInt(value);

    // Formata como moeda brasileira
    input.value = new Intl.NumberFormat("pt-BR", {
      style: "currency",
      currency: "BRL",
    }).format(amount / 100);
  }

  /**
   * Formatação de números (quantidade, estoque, etc.)
   */
  setupNumberFormatter() {
    // Event delegation para elementos existentes e futuros
    document.addEventListener("input", (e) => {
      if (e.target.classList.contains("number-input")) {
        this.formatNumber(e.target);
      }
    });

    document.addEventListener("keyup", (e) => {
      if (e.target.classList.contains("number-input")) {
        this.formatNumber(e.target);
      }
    });

    document.addEventListener("focus", (e) => {
      if (e.target.classList.contains("number-input") && !e.target.value) {
        e.target.placeholder = "0";
      }
    });

    // Aplica formatação em elementos já existentes
    setTimeout(() => {
      const numberInputs = document.querySelectorAll(".number-input");
      numberInputs.forEach((input) => {
        if (input.value) {
          this.formatNumber(input);
        }
      });
    }, 100);
  }

  formatNumber(input) {
    let value = input.value.replace(/\D/g, ""); // Remove tudo que não é dígito

    if (value === "") {
      input.value = "";
      return;
    }

    // Adiciona separadores de milhares
    input.value = parseInt(value).toLocaleString("pt-BR");
  }

  /**
   * Placeholders dinâmicos baseados no tipo de input
   */
  setupAutoPlaceholders() {
    document.addEventListener("DOMContentLoaded", () => {
      this.addDynamicPlaceholders();
    });
  }

  addDynamicPlaceholders() {
    // Placeholders para campos específicos
    const placeholders = {
      nome: "Nome completo",
      email: "email@exemplo.com",
      telefone: "Telefone (opcional)",
      endereco: "Endereço completo",
      cidade: "Cidade",
      estado: "Estado",
      cep: "00000-000",
      cpf: "000.000.000-00",
      cnpj: "00.000.000/0000-00",
      valor_venda: "R$ 0,00",
      valor_custo: "R$ 0,00",
      quantidade: "0",
      estoque_minimo: "0",
      descricao: "Descrição do produto",
      categoria: "Categoria do produto",
      marca: "Marca do produto",
      codigo_barras: "Código de barras",
      observacoes: "Observações adicionais",
    };

    // Aplica placeholders baseados no name do input
    Object.keys(placeholders).forEach((key) => {
      const inputs = document.querySelectorAll(
        `input[name="${key}"], input[name="${key}[]"]`
      );
      inputs.forEach((input) => {
        if (!input.placeholder) {
          input.placeholder = placeholders[key];
        }
      });
    });
  }

  /**
   * Validação em tempo real
   */
  setupValidation() {
    document.addEventListener("blur", (e) => {
      if (e.target.tagName === "INPUT") {
        this.validateField(e.target);
      }
    });
  }

  validateField(input) {
    const value = input.value.trim();
    const fieldName = input.name;

    // Validações específicas
    if (fieldName === "email" && value) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(value)) {
        this.showFieldError(input, "Email inválido");
      } else {
        this.clearFieldError(input);
      }
    }

    if (fieldName === "cpf" && value) {
      if (!this.isValidCPF(value)) {
        this.showFieldError(input, "CPF inválido");
      } else {
        this.clearFieldError(input);
      }
    }

    if (fieldName === "cnpj" && value) {
      if (!this.isValidCNPJ(value)) {
        this.showFieldError(input, "CNPJ inválido");
      } else {
        this.clearFieldError(input);
      }
    }
  }

  showFieldError(input, message) {
    this.clearFieldError(input);

    const errorDiv = document.createElement("div");
    errorDiv.className = "field-error text-red-500 text-xs mt-1";
    errorDiv.textContent = message;

    input.parentNode.appendChild(errorDiv);
    input.classList.add("border-red-500");
  }

  clearFieldError(input) {
    const errorDiv = input.parentNode.querySelector(".field-error");
    if (errorDiv) {
      errorDiv.remove();
    }
    input.classList.remove("border-red-500");
  }

  // Validação de CPF
  isValidCPF(cpf) {
    cpf = cpf.replace(/\D/g, "");
    if (cpf.length !== 11) return false;

    // Verifica se todos os dígitos são iguais
    if (/^(\d)\1{10}$/.test(cpf)) return false;

    // Validação do algoritmo
    let sum = 0;
    for (let i = 0; i < 9; i++) {
      sum += parseInt(cpf.charAt(i)) * (10 - i);
    }
    let remainder = 11 - (sum % 11);
    if (remainder === 10 || remainder === 11) remainder = 0;
    if (remainder !== parseInt(cpf.charAt(9))) return false;

    sum = 0;
    for (let i = 0; i < 10; i++) {
      sum += parseInt(cpf.charAt(i)) * (11 - i);
    }
    remainder = 11 - (sum % 11);
    if (remainder === 10 || remainder === 11) remainder = 0;
    if (remainder !== parseInt(cpf.charAt(10))) return false;

    return true;
  }

  // Validação de CNPJ
  isValidCNPJ(cnpj) {
    cnpj = cnpj.replace(/\D/g, "");
    if (cnpj.length !== 14) return false;

    // Verifica se todos os dígitos são iguais
    if (/^(\d)\1{13}$/.test(cnpj)) return false;

    // Validação do algoritmo
    let sum = 0;
    let weight = 2;
    for (let i = 11; i >= 0; i--) {
      sum += parseInt(cnpj.charAt(i)) * weight;
      weight = weight === 9 ? 2 : weight + 1;
    }
    let remainder = sum % 11;
    let digit1 = remainder < 2 ? 0 : 11 - remainder;
    if (digit1 !== parseInt(cnpj.charAt(12))) return false;

    sum = 0;
    weight = 2;
    for (let i = 12; i >= 0; i--) {
      sum += parseInt(cnpj.charAt(i)) * weight;
      weight = weight === 9 ? 2 : weight + 1;
    }
    remainder = sum % 11;
    let digit2 = remainder < 2 ? 0 : 11 - remainder;
    if (digit2 !== parseInt(cnpj.charAt(13))) return false;

    return true;
  }
}

// Inicializa o sistema de formatação apenas uma vez
let formatterInitialized = false;

function initializeFormatter() {
  if (!formatterInitialized) {
    new InputFormatter();
    formatterInitialized = true;
  }
}

// Inicializa quando o DOM estiver pronto
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initializeFormatter);
} else {
  initializeFormatter();
}

// Re-inicializa quando modais são abertos (para elementos dinâmicos)
document.addEventListener("click", (e) => {
  if (
    e.target.matches(
      "[data-modal-target], .open-modal-btn, .quick-add-cliente-btn, .quick-add-produto-btn"
    )
  ) {
    setTimeout(() => {
      new InputFormatter();
    }, 200);
  }
});

// Também re-inicializa quando elementos com phone-input são focados
document.addEventListener("focus", (e) => {
  if (e.target.classList.contains("phone-input")) {
    setTimeout(() => {
      new InputFormatter();
    }, 100);
  }
}, true);

// Script simples para formatação de telefone (fallback)

// Função simples de formatação de telefone
function formatPhoneSimple(input) {
  let value = input.value.replace(/\D/g, "");
  if (value.length > 11) value = value.substring(0, 11);
  
  if (value.length <= 2) {
    input.value = value;
  } else if (value.length <= 6) {
    input.value = `(${value.substring(0, 2)}) ${value.substring(2)}`;
  } else if (value.length <= 10) {
    input.value = `(${value.substring(0, 2)}) ${value.substring(2, 6)}-${value.substring(6)}`;
  } else {
    input.value = `(${value.substring(0, 2)}) ${value.substring(2, 3)} ${value.substring(3, 7)}-${value.substring(7)}`;
  }
}

// Aplica formatação simples em todos os campos phone-input
document.addEventListener("input", (e) => {
  if (e.target.classList.contains("phone-input")) {
    formatPhoneSimple(e.target);
  }
});

document.addEventListener("keyup", (e) => {
  if (e.target.classList.contains("phone-input")) {
    formatPhoneSimple(e.target);
  }
});
