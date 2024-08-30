<template>
  <div id="master-blogs">
    <h1>Create Blogs</h1>

    <AdminNav>
      <form class="admin-form" enctype="multipart/form-data" @submit.prevent="save">
        <div class="admin-input">
          <label for="featured_image">Featured Image</label><br />
          <input type="file" id="featured_image" @change="handleImageUpload" />

          <small class="errors-feedback" v-if="errors?.featured_image">
            {{ errors.featured_image[0] }}
          </small>
        </div>

        <div class="admin-input">
          <label for="title">Title</label>
          <input type="text" id="title" v-model="blog.title" />

          <small class="errors-feedback" v-if="errors?.title">
            {{ errors.title[0] }}
          </small>
        </div>

        <div class="admin-input">
          <label for="description">Description</label>
          <input type="text" id="description" v-model="blog.description" />

          <small class="errors-feedback" v-if="errors?.description">
            {{ errors.description[0] }}
          </small>
        </div>

        <div class="admin-input">
          <label for="tags">Tags</label>
          <input type="text" id="tags" v-model="blog.tags" placeholder="Separated with comma" />

          <small class="errors-feedback" v-if="errors?.tags">
            {{ errors.tags[0] }}
          </small>
        </div>

        <button type="submit">Submit</button>
      </form>
    </AdminNav>
  </div>
</template>

<script setup>
import AdminNav from '@/components/AdminNav.vue'
import { storeBlog } from '@/services/blog'
import { ref } from 'vue'
import { useRoute } from 'vue-router'

const blog = ref({
  featured_image: '',
  title: '',
  description: '',
  tags: ''
})
const errors = ref([])
const route = useRoute()

const handleImageUpload = (event) => {
  const file = event.target.files[0]

  if (file) {
    blog.value.featured_image = file
  }
}

async function save() {
  await storeBlog({
    id: route.params.id,
    data: {
      featured_image: blog.value.featured_image,
      title: blog.value.title,
      description: blog.value.description,
      tags: blog.value.tags
    }
  }).then((json) => {
    if (json.errors != null) {
      errors.value = json.errors
    }

    if (json.data != null) {
      alert('success')
      window.location.href = `/admin/blog`
    }

    if (json.exception != null) {
      alert(json.exception)
    }
  })
}
</script>