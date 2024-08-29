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

  <footer class="container">Copyright &copy; {{ new Date().getFullYear() }}</footer>
</template>

<script setup>
import { useCounterStore } from '@/stores/counter'

const counter = useCounterStore()

counter.count++
counter.$patch({ count: counter.count + 1 })
counter.increment()
</script>
