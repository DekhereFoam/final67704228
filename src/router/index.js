import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const routes = [
  {
    path: '/h',
    name: 'home',
    component: HomeView
  },
  {
    path: '/employees',
    name: 'employees',
    component: () => import( '../views/employees.vue')
  },
  {
    path: '/show',
    name: 'Showemployees',
    component: () => import(/* webpackChunkName: "about" */ '../views/Showemployees.vue')
  },
  {
    path: '/about',
    name: 'about',
    component: () => import(/* webpackChunkName: "about" */ '../views/AboutView.vue')
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
