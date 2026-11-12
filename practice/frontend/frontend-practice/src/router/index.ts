import Create from '@/pages/Create.vue'
import Edit from '@/pages/Edit.vue'
import Index from '@/pages/Index.vue'
import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: Index
    },
    {
      path: '/posts/create',
      component: Create
    },
    {
      path: '/posts/edit/:id',
      component: Edit,
      name: 'edit',
      props: true
    }
  ],
})

export default router
