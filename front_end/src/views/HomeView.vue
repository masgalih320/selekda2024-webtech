<template>
  <section v-if="banners" class="banners">
    <div class="slider-container">
      <ul :style="{ transform: `translateX(-${currentBannerIndex * 100}%)` }">
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

  <section id="search">
    <form action="" method="">
      <input type="text" class="search-input" />
      <button type="submit">Search</button>
    </form>
  </section>

  <section class="about">
    <h2>About</h2>
  </section>

  <section class="news">
    <h2>News</h2>

    <div class="news-cards">
      <article class="featured">
        <img src="@/assets/img/news/images-1.jpg" alt="Featured news" />

        <div class="featured-content">
          <a href="#">
            <h1>Apakah robot bisa kebelet boker?</h1>
          </a>
          <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Architecto, eveniet.</p>
        </div>
      </article>

      <div class="news-list">
        <article class="news-item">
          <div class="news-content">
            <h1>Lorem ipsum dolor sit amet.</h1>
            <p>27 Agustus 2024</p>
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Recusandae, error. Tempore...
            </p>
          </div>

          <div class="news-image">
            <img src="@/assets/img/news/images-2.png" alt="News 1" />
          </div>
        </article>

        <article class="news-item">
          <div class="news-content">
            <h1>Lorem ipsum dolor sit amet.</h1>
            <p>27 Agustus 2024</p>
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Recusandae, error. Tempore...
            </p>
          </div>

          <div class="news-image">
            <img src="@/assets/img/news/images-2.png" alt="News 1" />
          </div>
        </article>
      </div>
    </div>
  </section>

  <section class="services">
    <h2>Services</h2>
  </section>

  <section class="testimonial">
    <h2>Testimonials</h2>
    <p>What they say about us</p>

    <div class="testimonial-grids">
      <div class="card">
        <h3>Rahmat Tahalu</h3>
        <p class="job">Web3 Enthusiast</p>

        <p class="content">
          Lorem, ipsum dolor sit amet consectetur adipisicing elit. Consectetur possimus obcaecati
          quaerat earum. Natus harum assumenda ad ratione, recusandae dolore!
        </p>
      </div>

      <div class="card">
        <h3>Rahmat Tahalu</h3>
        <p class="job">Web3 Enthusiast</p>

        <p class="content">
          Lorem, ipsum dolor sit amet consectetur adipisicing elit. Consectetur possimus obcaecati
          quaerat earum. Natus harum assumenda ad ratione, recusandae dolore!
        </p>
      </div>

      <div class="card">
        <h3>Rahmat Tahalu</h3>
        <p class="job">Web3 Enthusiast</p>

        <p class="content">
          Lorem, ipsum dolor sit amet consectetur adipisicing elit. Consectetur possimus obcaecati
          quaerat earum. Natus harum assumenda ad ratione, recusandae dolore!
        </p>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { fetchBanner } from '@/services/banner'
import fetchPortfolio from '@/services/portfolio'

const banners = ref([])
const portfolio = ref([])
const currentBannerIndex = ref(0)

function prevSlide() {
  if (currentBannerIndex.value > 0) {
    currentBannerIndex.value -= 1
  } else {
    currentBannerIndex.value = banners.value.data.length - 1
  }
}

function nextSlide() {
  if (currentBannerIndex.value < banners.value.data.length - 1) {
    currentBannerIndex.value += 1
  } else {
    currentBannerIndex.value = 0
  }
}

onMounted(() => {
  fetchBanner().then((json) => {
    banners.value = json
  })

  fetchPortfolio().then((json) => {
    portfolio.value = json
  })
})
</script>

