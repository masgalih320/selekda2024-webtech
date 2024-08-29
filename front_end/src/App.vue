<template>
  <header class="header-top container">
    <RouterLink to="/">
      <img alt="WebtechID" class="logo" src="@/assets/logo.png" height="50" />
    </RouterLink>

    <nav class="header-nav">
      <RouterLink to="/">Home</RouterLink>
      <RouterLink to="/about">About</RouterLink>
    </nav>
  </header>

  <main class="container">
    <RouterView />
  </main>

  <footer class="container">
    <p>Copyright &copy; {{ new Date().getFullYear() }}</p>
    <p>Designed by <a href="https://galih.me" class="url-primary">Galih Sukristyan Saputra</a></p>
  </footer>
</template>