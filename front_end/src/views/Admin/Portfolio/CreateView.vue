<template>
  <div id="master-portfolio">
    <h1>Create Portfolio</h1>

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
import { storePortfolio } from '@/services/portfolio'
import { ref } from 'vue'
import { useRoute } from 'vue-router'

const portfolio = ref({
  title: '',
  image: '',
  description: ''
})
const errors = ref([])
const route = useRoute()

const handleImageUpload = (event) => {
  const file = event.target.files[0]

  if (file) {
    portfolio.value.image = file
  }
}

async function save() {
  await storePortfolio({
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
</script>