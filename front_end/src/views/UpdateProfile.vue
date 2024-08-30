<template>
  <form class="card-login" @submit.prevent="save">
    <h1>Update Profile</h1>

    <div class="login-form">
      <label for="name">Name</label>
      <input type="text" id="name" placeholder="Name" v-model="profile.name" autocomplete="name" />

      <small class="errors-feedback" v-if="errors.name">{{ errors.name[0] }}</small>
    </div>

    <div class="login-form">
      <label for="phone">Phone</label>
      <input
        type="number"
        id="phone"
        placeholder="Phone"
        v-model="profile.phone"
        autocomplete="tel"
      />

      <small class="errors-feedback" v-if="errors.phone">{{ errors.phone[0] }}</small>
    </div>

    <div class="login-form">
      <label for="date_of_birth">Date of birth</label>
      <input
        type="date"
        id="date_of_birth"
        placeholder="Date of birth"
        v-model="profile.date_of_birth"
        autocomplete="bday-day"
      />

      <small class="errors-feedback" v-if="errors.date_of_birth">
        {{ errors.date_of_birth[0] }}
      </small>
    </div>

    <div class="login-form">
      <label for="profile_picture">Profile Picture</label>
      <input
        type="file"
        id="profile_picture"
        placeholder="Profile Picture"
        @change="handleImageUpload"
      />

      <small class="errors-feedback" v-if="errors.profile_picture">
        {{ errors.profile_picture[0] }}
      </small>
    </div>

    <button type="submit">Submit</button>
  </form>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { fetchProfile, updateProfile } from '@/services/profile'
import router from '@/router'

const profile = ref([])
const errors = ref([])

onMounted(() => {
  fetchProfile().then((json) => {
    profile.value = json.data
    profile.value.profile_picture = ''
  })
})

const handleImageUpload = (event) => {
  const file = event.target.files[0]

  if (file) {
    profile.value.profile_picture = file
  }
}

async function save() {
  errors.value = []
  document.querySelector('[type="submit"]').textContent = 'Loading'

  await updateProfile({
    data: {
      name: profile.value.name,
      phone: profile.value.phone,
      date_of_birth: profile.value.date_of_birth,
      profile_picture: profile.value.profile_picture
    }
  }).then((json) => {
    if (json.errors != null) {
      errors.value = json.errors
    }

    if (json.data != null) {
      alert('success')
      window.location.reload()
    }

    if (json.exception != null) {
      alert(json.exception)
    }
  })
}
</script>