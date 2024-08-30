<template>
  <header class="header-top container">
    <RouterLink to="/">
      <img alt="WebtechID" class="logo" src="@/assets/img/logo.png" height="50" />
    </RouterLink>

    <nav class="header-nav">
      <RouterLink to="/">Home</RouterLink>
      <RouterLink to="/blog">Blog</RouterLink>
      <RouterLink to="/login" v-if="userdata == null">Login</RouterLink>
      <RouterLink to="/register" v-if="userdata == null">Register</RouterLink>
      <RouterLink to="/admin" v-if="userdata?.user?.roles == 'administrator'">
        Admin Dashboard
      </RouterLink>
      <RouterLink to="/logout" v-if="userdata != null">Logout</RouterLink>
    </nav>
  </header>

  <main class="container">
    <RouterView />
  </main>

  <footer class="container">
    <div class="socials">
      <a href="https://instagram.com/5gspt">
        <img src="@/assets/icons/instagram.png" alt="Instagram" height="30" />
      </a>

      <a href="https://facebook.com/5gspt">
        <img src="@/assets/icons/fb.png" alt="Facebook" height="30" />
      </a>

      <a href="https://twitter.com/galihsptr320">
        <img src="@/assets/icons/twitter.png" alt="Twitter" height="30" />
      </a>

      <a href="https://youtube.com/@galihsptr320">
        <img src="@/assets/icons/youtube.png" alt="Youtube" height="30" />
      </a>

      <a href="https://linkedin.com/in/galihsptr320">
        <img src="@/assets/icons/linkedin.png" alt="LinkedIn" height="30" />
      </a>
    </div>

    <p>Copyright &copy; {{ new Date().getFullYear() }}</p>
    <p>Designed by <a href="https://galih.me" class="url-primary">Galih Sukristyan Saputra</a></p>
  </footer>
</template>

<script setup>
import currentUser from '@/services/currentUser'
import { onMounted, ref, watch } from 'vue'
import { useRoute } from 'vue-router'

const userdata = ref([])
const route = useRoute()

onMounted(async () => {
  userdata.value = await currentUser()
})

watch(
  () => route.fullPath,
  async () => {
    userdata.value = await currentUser()
  }
)
</script>
