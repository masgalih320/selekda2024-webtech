<template>
  <div class="admin-wrap">
    <ul class="admin-nav">
      <li>
        <h2>Admin Menu</h2>
      </li>

      <li>
        <router-link to="/admin">Admin Dashboard</router-link>
      </li>

      <li>
        <router-link to="/admin/banner">Master Banner</router-link>
      </li>

      <li>
        <router-link to="/admin/blog">Master Blog</router-link>
      </li>

      <li>
        <router-link to="/admin/portfolio">Master Portfolio</router-link>
      </li>

      <li>
        <router-link to="/">Master User</router-link>
      </li>
    </ul>

    <div class="admin-container">
      <slot />
    </div>
  </div>
</template>