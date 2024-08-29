<template>
  <section v-if="banners" class="banners">
    <div class="slider-container">
      <ul :style="{ transform: `translateX(-${currentIndex * 100}%)` }">
        <li v-for="(banner, index) in banners.data" :key="index" class="slider-item">
          <img :src="banner.image_url" :alt="banner.title" />
          <div class="slider-content">
            <h2>{{ banner.title }}</h2>
            <p>{{ banner.description }}</p>
          </div>
        </li>
      </ul>

      <div class="controls">
        <button @click="prevSlide" class="previous">&lt;</button>
        <button @click="nextSlide" class="next">&gt;</button>
      </div>
    </div>
  </section>

  <section class="news">
    <h2>News</h2>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import fetchBanner from '@/services/banner'

const banners = ref([])

const currentIndex = ref(0)

function prevSlide() {
  if (currentIndex.value > 0) {
    currentIndex.value -= 1
  } else {
    currentIndex.value = banners.value.data.length - 1
  }
}

function nextSlide() {
  if (currentIndex.value < banners.value.data.length - 1) {
    currentIndex.value += 1
  } else {
    currentIndex.value = 0
  }
}

onMounted(() => {
  fetchBanner().then((json) => {
    banners.value = json
  })
})
</script>

