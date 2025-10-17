<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR" data-theme="light-blue">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Master Control - Registro</title>
  <link rel="stylesheet" href="/src/styles/tailwind.css">
  <link rel="stylesheet" href="/src/styles/styles.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap"
    rel="stylesheet" />
</head>

<body
  class="flex items-center justify-center min-h-screen bg-[var(--color-background)]">
  <div class="w-full max-w-md p-4">
    <div class="bg-[var(--color-surface)] shadow-xl rounded-lg p-8 space-y-6">
      <div class="text-center">
        <div class="w-20 h-20 mx-auto mb-4">
          <img
            src="../../../assets/masterControlicon.png"
            alt="Master Control Logo"
            class="w-full h-full object-contain" />
        </div>
        <h1 class="text-3xl font-bold text-[var(--color-text-primary)]">
          Bem-vindo ao Master Control!
        </h1>
        <p class="text-[var(--color-text-secondary)]">
          Crie sua conta para gerenciar seu estoque e vendas.
        </p>
      </div>

      <form action="/src/actions/auth/process_register.php" method="POST" class="space-y-4">
        <div>
          <label
            for="fullName"
            class="block text-sm font-medium text-[var(--color-text-secondary)]">Nome Completo</label>
          <input
            type="text"
            id="fullName"
            name="fullName"
            required
            class="mt-1 block w-full px-3 py-2 bg-[var(--color-surface)] border border-[var(--color-border)] text-[var(--color-text-primary)] rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)] focus:border-[var(--color-primary)] sm:text-sm"
            placeholder="Seu nome" />
        </div>

        <div>
          <label
            for="email"
            class="block text-sm font-medium text-[var(--color-text-secondary)]">Email</label>
          <input
            type="email"
            id="email"
            name="email"
            required
            class="mt-1 block w-full px-3 py-2 bg-[var(--color-surface)] border border-[var(--color-border)] text-[var(--color-text-primary)] rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)] focus:border-[var(--color-primary)] sm:text-sm"
            placeholder="seu@email.com" />
        </div>

        <div>
          <label
            for="password"
            class="block text-sm font-medium text-[var(--color-text-secondary)]">Senha</label>
          <input
            type="password"
            id="password"
            name="password"
            required
            class="mt-1 block w-full px-3 py-2 bg-[var(--color-surface)] border border-[var(--color-border)] text-[var(--color-text-primary)] rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)] focus:border-[var(--color-primary)] sm:text-sm"
            placeholder="••••••••" />
        </div>

        <div>
          <label
            for="confirm_password"
            class="block text-sm font-medium text-[var(--color-text-secondary)]">Confirmar Senha</label>
          <input
            type="password"
            id="confirm_password"
            name="confirm_password"
            required
            class="mt-1 block w-full px-3 py-2 bg-[var(--color-surface)] border border-[var(--color-border)] text-[var(--color-text-primary)] rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)] focus:border-[var(--color-primary)] sm:text-sm"
            placeholder="••••••••" />
        </div>

        <div>
          <label
            for="account_type"
            class="block text-sm font-medium text-[var(--color-text-secondary)]">Tipo de Conta</label>
          <select
            id="account_type"
            name="account_type"
            required
            class="mt-1 block w-full px-3 py-2 bg-[var(--color-surface)] border border-[var(--color-border)] text-[var(--color-text-primary)] rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)] focus:border-[var(--color-primary)] sm:text-sm">
            <option value="">Selecione</option>
            <option value="PF">Pessoa Física (PF)</option>
            <option value="PJ">Pessoa Jurídica (PJ)</option>
          </select>
        </div>

        <div>
          <button
            type="submit"
            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[var(--color-primary)] hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--color-primary)]">
            Registrar
          </button>
        </div>
      </form>

      <div class="text-center text-sm text-[var(--color-text-secondary)]">
        Já tem uma conta?
        <a
          href="../login"
          class="font-medium text-[var(--color-primary)] hover:underline">Faça login aqui</a>
      </div>
    </div>
  </div>
</body>

</html>