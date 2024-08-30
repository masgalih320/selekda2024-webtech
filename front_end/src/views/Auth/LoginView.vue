<template>
  <form class="card-login" @submit.prevent="login">
    <h1>Login</h1>

    <div class="login-form">
      <label for="username">Username</label>
      <input
        type="text"
        id="username"
        placeholder="Username"
        v-model="username"
        autocomplete="username"
      />

      <small class="errors-feedback" v-if="errors.username">{{ errors.username[0] }}</small>
    </div>

    <div class="login-form">
      <label for="password">Password</label>
      <input
        type="password"
        id="password"
        placeholder="Password"
        v-model="password"
        autocomplete="current-password"
      />

      <small class="errors-feedback" v-if="errors.password">{{ errors.password[0] }}</small>
    </div>

    <button type="submit">Submit</button>
  </form>
</template>

<script setup>
import { ref } from 'vue'
import fetchLogin from '@/services/auth'
import router from '@/router'

const username = ref('')
const password = ref('')
const errors = ref([])

function login() {
  errors.value = []
  document.querySelector('[type="submit"]').textContent = 'Loading'

  fetchLogin({
    username: username.value,
    password: password.value
  }).then((json) => {
    document.querySelector('[type="submit"]').textContent = 'Submit'

    if (json.errors != undefined) {
      errors.value = json.errors
    }

    if (json.data != undefined) {
      localStorage.setItem('selekda_session', JSON.stringify(json.data))
      router.push('admin')
    }
  })
}
</script>