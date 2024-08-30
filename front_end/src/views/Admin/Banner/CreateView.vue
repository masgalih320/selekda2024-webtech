<template>
  <div id="master-banner">
    <h1>Create Banner</h1>

    <AdminNav>
      <form class="admin-form" enctype="multipart/form-data" @submit.prevent="save">
        <div class="admin-input">
          <label for="title">Title</label>
          <input type="text" id="title" v-model="banner.title" />

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
          <input type="text" id="description" v-model="banner.description" />

          <small class="errors-feedback" v-if="errors?.description">
            {{ errors.description[0] }}
          </small>
        </div>

        <div class="admin-input">
          <label for="status">Status</label>
          <select id="status" v-model="banner.status">
            <option value="">Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>

          <small class="errors-feedback" v-if="errors?.status">
            {{ errors.status[0] }}
          </small>
        </div>

        <button type="submit">Submit</button>
      </form>
    </AdminNav>
  </div>
</template>

<script setup>
import AdminNav from '@/components/AdminNav.vue'
import { storeBanner } from '@/services/banner'
import { ref } from 'vue'
import { useRoute } from 'vue-router'

const banner = ref({
  title: '',
  image: '',
  description: '',
  status: ''
})
const errors = ref([])
const route = useRoute()

const handleImageUpload = (event) => {
  const file = event.target.files[0]

  if (file) {
    banner.value.image = file
  }
}

async function save() {
  await storeBanner({
    id: route.params.id,
    data: {
      title: banner.value.title,
      image: banner.value.image,
      description: banner.value.description,
      status: banner.value.status
    }
  }).then((json) => {
    if (json.errors != null) {
      errors.value = json.errors
    }

    if (json.data != null) {
      alert('success')
      window.location.href = `/admin/banner`
    }

    if (json.exception != null) {
      alert(json.exception)
    }
  })
}
</script>