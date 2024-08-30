import { createRouter, createWebHistory } from 'vue-router';

const isAuthenticated = () => {
  const session = localStorage.getItem('selekda_session');
  return !!session;
};

const isAdmin = () => {
  const session = localStorage.getItem('selekda_session');
  if (session) {
    const user = JSON.parse(session);
    return user.user.roles === 'administrator';
  }
  return false;
};

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('../views/HomeView.vue'),
    },
    {
      path: '/update_profile',
      name: 'update_profile',
      component: () => import('../views/UpdateProfile.vue'),
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/Auth/LoginView.vue'),
    },
    {
      path: '/logout',
      name: 'logout',
      component: () => import('../views/Auth/LogoutView.vue'),
    },
    {
      path: '/admin',
      name: 'admin',
      beforeEnter: (to, from, next) => {
        if (!isAuthenticated() || !isAdmin()) {
          next({ name: 'login' });
        } else {
          next();
        }
      },
      children: [
        {
          path: '',
          name: 'dashboard',
          component: () => import('../views/Admin/DashboardView.vue'),
        },
        {
          path: 'banner',
          name: 'banner',
          children: [
            {
              path: '',
              name: 'list_banner',
              component: () => import('../views/Admin/Banner/IndexView.vue'),
            },
            {
              path: 'create',
              name: 'create_banner',
              component: () => import('../views/Admin/Banner/CreateView.vue'),
            },
            {
              path: 'edit/:id',
              name: 'edit_banner',
              component: () => import('../views/Admin/Banner/EditView.vue'),
            },
          ]
        },
        {
          path: 'blog',
          name: 'blog',
          children: [
            {
              path: '',
              name: 'list_blog',
              component: () => import('../views/Admin/Blog/IndexView.vue'),
            },
            {
              path: 'create',
              name: 'create_blog',
              component: () => import('../views/Admin/Blog/CreateView.vue'),
            },
            {
              path: 'edit/:id',
              name: 'edit_blog',
              component: () => import('../views/Admin/Blog/EditView.vue'),
            },
          ]
        },
        {
          path: 'portfolio',
          name: 'portfolio',
          children: [
            {
              path: '',
              name: 'list_portfolio',
              component: () => import('../views/Admin/Portfolio/IndexView.vue'),
            },
            {
              path: 'create',
              name: 'create_portfolio',
              component: () => import('../views/Admin/Portfolio/CreateView.vue'),
            },
            {
              path: 'edit/:id',
              name: 'edit_portfolio',
              component: () => import('../views/Admin/Portfolio/EditView.vue'),
            },
          ]
        },
      ],
    },
  ],
});

export default router;
