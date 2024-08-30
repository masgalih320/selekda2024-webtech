<template></template>

<script setup>
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

onMounted(() => {
  localStorage.removeItem('selekda_session')
  router.push({ name: 'login' })
})
</script>
