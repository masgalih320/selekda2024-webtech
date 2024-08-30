<template>
  <div id="master-portfolio">
    <div class="page-title">
      <h1>Master Portfolio</h1>
      <router-link to="/admin/portfolio/create" class="btn primary">Add</router-link>
    </div>

    <AdminNav>
      <table>
        <thead>
          <tr>
            <th>NO</th>
            <th>TITLE</th>
            <th>IMAGE</th>
            <th>DESCRIPTION</th>
            <th>CREATED AT</th>
            <th>MANAGE</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="(portfolio, index) in portfolios" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ portfolio.title }}</td>
            <td>
              <img :src="portfolio.image_url" :alt="portfolio.title" width="100" />
            </td>
            <td>{{ portfolio.description }}</td>
            <td>
              {{
                new Date(portfolio.created_at).toLocaleDateString('id-ID', {
                  day: '2-digit',
                  month: 'long',
                  year: 'numeric'
                })
              }}
            </td>
            <td>
              <router-link :to="`/admin/portfolio/edit/${portfolio.id}`" class="btn edit">
                Edit
              </router-link>
              <button @click="rm(portfolio.id)" type="button" class="btn delete">Delete</button>
            </td>
          </tr>

          <tr v-if="portfolios.length == 0">
            <td colspan="7">NO DATA</td>
          </tr>
        </tbody>
      </table>
    </AdminNav>
  </div>
</template>

<script setup>
import AdminNav from '@/components/AdminNav.vue'
import { fetchPortfolio, deletePortfolio } from '@/services/portfolio'
import { onMounted, ref } from 'vue'

const portfolios = ref([])

onMounted(async () => {
  const fetchedPortfolios = await fetchPortfolio()
  portfolios.value = fetchedPortfolios.data.sort(
    (a, b) => new Date(b.created_at) - new Date(a.created_at)
  )
})

async function rm(id) {
  if (confirm('Are you sure?')) {
    await deletePortfolio(id).then((json) => {
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