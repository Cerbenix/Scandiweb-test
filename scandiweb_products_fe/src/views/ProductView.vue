<template>
  <CustomHeader>
    <h1>Product List</h1>
    <CustomNav>
      <LinkButton href="/product/create">ADD</LinkButton>
      <CustomButton @custom-click="handleMassDelete">MASS DELETE</CustomButton>
    </CustomNav>
  </CustomHeader>
  <ul v-if="!error" class="product-list">
    <li v-for="product in productCollection" :key="product.id">
      <ProductCard :product-info="product" @checkbox-change="handleCheckboxChange" />
    </li>
  </ul>
  <div v-if="error">
    <p>{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
import CustomHeader from '@/components/CustomHeader.vue';
import CustomButton from '@/components/CustomButton.vue';
import CustomNav from '@/components/CustomNav.vue';
import LinkButton from '@/components/LinkButton.vue';
import ProductCard from '@/components/ProductCard.vue';
import { onMounted, ref } from 'vue';

export type Product = {
  id: number
  sku: string
  name: string
  price: number
  type: string
}

export type Book = Product & {
  weight: string
}

export type Dvd = Product & {
  size: string
}

export type Furniture = Product & {
  dimensions: string
}

export type AllProductTypes = Book | Dvd | Furniture;

const productCollection = ref<(AllProductTypes)[]>([])
const error = ref<string>('')
const selectedProducts = ref<number[]>([]);

const fetchProducts = async () => {
  try {
    const response = await fetch('/api/products')
    const result = await response.json()
    if (!response.ok) {
      error.value = 'Something went wrong'
    }
    productCollection.value = result.map((item: AllProductTypes) => {
      return { ...item }
    })

  } catch (exception) {
    error.value = 'Something went wrong'
  }
}

const handleCheckboxChange = (productId: number, isChecked: boolean) => {
  if (isChecked) {
    selectedProducts.value.push(productId);
  } else {
    const index = selectedProducts.value.indexOf(productId);
    if (index !== -1) {
      selectedProducts.value.splice(index, 1);
    }
  }
};

const handleMassDelete = async () => {
  if(selectedProducts.value.length === 0){
    return
  }
  try {
    const response = await fetch('/api/products/delete', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(selectedProducts.value)
    });
    if (!response.ok) {
      error.value = 'Failed to delete products'
    }
    window.location.reload();
  } catch (exception) {
    error.value = 'Failed to delete products'
  } finally {
    selectedProducts.value = [];
  }
};

onMounted(() => {
  fetchProducts()
});
</script>

<style>
.product-list {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 50px;
  padding: 0;
  margin: 0 5%;
  align-items: center;
}

.product-list li {
  list-style-type: none;
}

@media (max-width: 1300px) {
  .product-list {
    grid-template-columns: repeat(4, 1fr);
    gap: 40px;
  }
}

@media (max-width: 1050px) {
  .product-list {
    grid-template-columns: repeat(3, 1fr);
    gap: 40px;
  }
}

@media (max-width: 800px) {
  .product-list {
    grid-template-columns: repeat(2, 1fr);
    gap: 30px;
  }
}

@media (max-width: 550px) {
  .product-list {
    grid-template-columns: repeat(1, 1fr);
  }
}
</style>