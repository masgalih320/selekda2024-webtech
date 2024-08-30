import { createRouter, createWebHistory } from 'vue-router'

const isAuthenticated = () => {
  return !!localStorage.getItem('selekda_session');
};

const isAdmin = () => {
  const session = localStorage.getItem('selekda_session');
  if (!session) return false;

  const user = JSON.parse(session);
  return user.user.roles === 'administrator';
};

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('../views/HomeView.vue')
    },
    {
      path: '/about',
      name: 'about',
      component: () => import('../views/AboutView.vue')
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/Auth/LoginView.vue'),
      beforeEnter: (to, from, next) => {
        if (isAuthenticated()) {
          next({ name: 'login' });
        } else {
          next();
        }
      },
    },
    {
      path: '/logout',
      name: 'logout',
      component: () => import('../views/Auth/LogoutView.vue'),
    },
    {
      path: '/admin',
      name: 'admin',
      component: () => import('../views/Admin/DashboardView.vue'),
      beforeEnter: (to, from, next) => {
        if (!isAuthenticated() && !isAdmin()) {
          next({ name: 'logout' });
        } else {
          next();
        }
      },
      children: [
        //
      ]
    },
  ]
})

export default router
