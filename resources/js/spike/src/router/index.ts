import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'

// Halaman contoh – buat file views/Home.vue pada langkah 4
const routes: RouteRecordRaw[] = [
  { path: '/', name: 'home', component: () => import('../views/Home.vue') },
]

export default createRouter({
  // Prefix SPA di bawah /teacher (agar URL Vue = /teacher/... )
  history: createWebHistory('/teacher'),
  routes,
})
