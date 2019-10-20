import VueRouter from 'vue-router'

const routes = [
  {
    path: '/',
    component: () => import(/* webpackChunkName: "page-index" */ './pages/index.vue'),
    name: 'root',
  },
  {
    path: '/login',
    component: () => import(/* webpackChunkName: "page-category" */ './pages/login.vue'),
    name: 'login',
  },
  {
    path: '/register',
    component: () => import(/* webpackChunkName: "page-category" */ './pages/register.vue'),
    name: 'register',
  },
];

const router = new VueRouter({
  mode: 'history',
  routes
});

export default router;
