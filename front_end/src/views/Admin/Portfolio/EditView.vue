<template>
  <div id="master-portfolio">
    <h1>Edit Portfolio</h1>

    <AdminNav>
      <form class="admin-form" enctype="multipart/form-data" @submit.prevent="save">
        <div class="admin-input">
          <label for="title">Title</label>
          <input type="text" id="title" v-model="portfolio.title" />

          <small class="errors-feedback" v-if="errors?.title">
            {{ errors.title[0] }}
          </small>
        </div>

        <div class="admin-input">
          <label for="image">Image</label><br />
          <img :src="portfolio.image_url" :alt="portfolio.title" width="200" />
          <input type="file" id="image" @change="handleImageUpload" />

          <small class="errors-feedback" v-if="errors?.image">
            {{ errors.image[0] }}
          </small>
        </div>

        <div class="admin-input">
          <label for="description">Description</label>
          <input type="text" id="description" v-model="portfolio.description" />

          <small class="errors-feedback" v-if="errors?.description">
            {{ errors.description[0] }}
          </small>
        </div>

        <button type="submit">Submit</button>
      </form>
    </AdminNav>
  </div>
</template>

<script setup>
import AdminNav from '@/components/AdminNav.vue'
import router from '@/router'
import { getPortfolio, updatePortfolio } from '@/services/portfolio'
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'

const portfolio = ref([])
const errors = ref([])
const route = useRoute()

const handleImageUpload = (event) => {
  const file = event.target.files[0]

  if (file) {
    portfolio.value.image = file
  }
}

async function save() {
  await updatePortfolio({
    id: route.params.id,
    data: {
      title: portfolio.value.title,
      image: portfolio.value.image,
      description: portfolio.value.description
    }
  }).then((json) => {
    if (json.errors != null) {
      errors.value = json.errors
    }

    if (json.data != null) {
      alert('success')
      window.location.href = `/admin/portfolio`
    }

    if (json.exception != null) {
      alert(json.exception)
    }
  })
}

onMounted(async () => {
  portfolio.value = await getPortfolio(route.params.id).then((json) => json.data)
  portfolio.value.image = ''
})
</script>