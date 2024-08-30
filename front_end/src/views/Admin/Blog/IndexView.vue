<template>
  <div id="master-blog">
    <div class="page-title">
      <h1>Master Blog</h1>
      <router-link to="/admin/blog/create" class="btn primary">Add</router-link>
    </div>

    <AdminNav>
      <table>
        <thead>
          <tr>
            <th>NO</th>
            <th>AUTHOR</th>
            <th>TITLE</th>
            <th>FEATURED IMAGE</th>
            <th>DESCRIPTION</th>
            <th>TAGS</th>
            <th>CREATED AT</th>
            <th>MANAGE</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="(blog, index) in blogs" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ blog.author }}</td>
            <td>{{ blog.title }}</td>
            <td>
              <img :src="blog.image_url" :alt="blog.title" width="100" />
            </td>
            <td>{{ blog.description }}</td>
            <td>{{ blog.tags.join(',') }}</td>
            <td>
              {{
                new Date(blog.created_at).toLocaleDateString('id-ID', {
                  day: '2-digit',
                  month: 'long',
                  year: 'numeric'
                })
              }}
            </td>
            <td>
              <router-link :to="`/admin/blog/edit/${blog.id}`" class="btn edit"> Edit </router-link>
              <button @click="rm(blog.id)" type="button" class="btn delete">Delete</button>
            </td>
          </tr>

          <tr v-if="blogs.length == 0">
            <td colspan="8">NO DATA</td>
          </tr>
        </tbody>
      </table>
    </AdminNav>
  </div>
</template>

<script setup>
import AdminNav from '@/components/AdminNav.vue'
import { deleteBlog, fetchBlog } from '@/services/blog'
import { onMounted, ref } from 'vue'

const blogs = ref([])

onMounted(async () => {
  const fetchedBlogs = await fetchBlog()
  blogs.value = fetchedBlogs.data.sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
})

async function rm(id) {
  if (confirm('Are you sure?')) {
    await deleteBlog(id).then((json) => {
      if (json.data?.deleted) {
        alert('success')
        window.location.reload()
      } else {
        alert('failed to delete')
      }
    })
  }
}
</script>