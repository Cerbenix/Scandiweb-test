import { createRouter, createWebHistory } from 'vue-router'
// @ts-ignore
import ProductView from '../views/ProductView.vue'
// @ts-ignore
import CreateProduct from '../views/CreateProductView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'Products',
      component: ProductView
    },
    {
      path: '/product/create',
      name: 'CreateProduct',
      component: CreateProduct
    }
  ]
})

export default router
