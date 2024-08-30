<template>
  <div id="master-banner">
    <div class="page-title">
      <h1>Master Banner</h1>
      <router-link to="/admin/banner/create" class="btn primary">Add</router-link>
    </div>

    <AdminNav>
      <table>
        <thead>
          <tr>
            <th>NO</th>
            <th>TITLE</th>
            <th>IMAGE</th>
            <th>DESCRIPTION</th>
            <th>STATUS</th>
            <th>CREATED AT</th>
            <th>MANAGE</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="(banner, index) in banners" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ banner.title }}</td>
            <td>
              <img :src="banner.image_url" :alt="banner.title" width="100" />
            </td>
            <td>{{ banner.description }}</td>
            <td>{{ banner.status }}</td>
            <td>
              {{
                new Date(banner.created_at).toLocaleDateString('id-ID', {
                  day: '2-digit',
                  month: 'long',
                  year: 'numeric'
                })
              }}
            </td>
            <td>
              <router-link :to="`/admin/banner/edit/${banner.id}`" class="btn edit">
                Edit
              </router-link>
              <button @click="rm(banner.id)" type="button" class="btn delete">Delete</button>
            </td>
          </tr>

          <tr v-if="banners.length == 0">
            <td colspan="7">NO DATA</td>
          </tr>
        </tbody>
      </table>
    </AdminNav>
  </div>
</template>

<script setup>
import AdminNav from '@/components/AdminNav.vue'
import { fetchBanner, deleteBanner } from '@/services/banner'
import { onMounted, ref } from 'vue'

const banners = ref([])

onMounted(async () => {
  const fetchedBanners = await fetchBanner()
  banners.value = fetchedBanners.data.sort(
    (a, b) => new Date(b.created_at) - new Date(a.created_at)
  )
})

async function rm(id) {
  if (confirm('Are you sure?')) {
    await deleteBanner(id).then((json) => {
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