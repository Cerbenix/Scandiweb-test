<template>
  <CustomHeader>
    <h1>Add Product</h1>
    <CustomNav>
      <CustomButton type="submit" form="product_form">Save</CustomButton>
      <LinkButton href="/">Cancel</LinkButton>
    </CustomNav>
  </CustomHeader>
  <div v-if="!error" class="product-form-container">

    <form id="product_form" @submit.prevent="handleSubmit">
      <div class="form-input-container">
        <label for="sku">SKU:</label>
        <input type="text" id="sku" name="sku" />
      </div>
      <p v-if="validationErrors.sku">{{ validationErrors.sku }}</p>

      <div class="form-input-container">
        <label for="price">Price:</label>
        <input type="text" id="price" name="price" />
      </div>
      <p v-if="validationErrors.price">{{ validationErrors.price }}</p>

      <div class="form-input-container">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" />
      </div>
      <p v-if="validationErrors.name">{{ validationErrors.name }}</p>

      <div class="form-input-container">
        <label for="productType">Product Type:</label>
        <select id="productType" v-model="selectedProductType" name="productType">
          <option v-for="productType in productTypes" :value="productType" :key="productType">
            {{ productType }}
          </option>
        </select>
      </div>
      <p v-if="validationErrors.type">{{ validationErrors.type }}</p>

      <div v-if="selectedProductType">
        <div v-if="selectedProductType === 'Book'">
          <div class="form-input-container">
            <label for="weight">Weight(kg):</label>
            <input type="text" id="weight" name="weight" />
          </div>
          <p v-if="validationErrors.weight">{{ validationErrors.weight }}</p>
        </div>

        <div v-if="selectedProductType === 'DVD'">
          <div class="form-input-container">
            <label for="size">Size(mb):</label>
            <input type="text" id="size" name="size" />
          </div>
          <p v-if="validationErrors.size">{{ validationErrors.size }}</p>
        </div>

        <div v-if="selectedProductType === 'Furniture'" class="multi-input-container">
          <div class="form-input-container">
            <label for="length">Length(cm):</label>
            <input type="text" id="length" name="length" />
          </div>

          <div class="form-input-container">
            <label for="width">Width(cm):</label>
            <input type="text" id="width" name="width" />
          </div>

          <div class="form-input-container">
            <label for="height">Height(cm):</label>
            <input type="text" id="height" name="height" />
          </div>
          <p v-if="validationErrors.dimensions">{{ validationErrors.dimensions }}</p>
        </div>

        <div class="product-description">
          <div v-if="selectedProductType === 'Book'">
            Please input the books weight in kg.
          </div>

          <div v-if="selectedProductType === 'DVD'">
            Please input the dvd size in mb.
          </div>

          <div v-if="selectedProductType === 'Furniture'">
            Please input the furniture dimensions in cm.
          </div>
        </div>
      </div>
    </form>
  </div>
  <div v-if="error">
    <p>{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
import CustomHeader from '@/components/CustomHeader.vue'
import CustomButton from '@/components/CustomButton.vue'
import CustomNav from '@/components/CustomNav.vue'
import LinkButton from '@/components/LinkButton.vue'
import { onMounted, ref, type Ref } from 'vue'
import type { AllProductTypes } from './ProductView.vue'
import { handleBookSubmition } from '@/utils/handleBookSubmition'
import { handleDvdSubmition } from '@/utils/handleDvdSubmition'
import { handleFurnitureSubmition } from '@/utils/handleFurnitureSubmition'

const productSkuCollection = ref<string[]>([])
const selectedProductType = ref<string>('')
const productTypes = ref<string[]>(['Book', 'DVD', 'Furniture'])
export interface ValidationErrors {
  [key: string]: string
}
const error: Ref<string> = ref('')
const validationErrors = ref<ValidationErrors >({})
type HandleFunction =(values: Record<string, string>, skuList: string[]) => { errors: ValidationErrors, product: null; } | { errors: null, product: Partial<AllProductTypes> };

const fetchProductSkuList = async () => {
  try {
    const response = await fetch('/api/products')
    const result = await response.json()
    if (!response.ok) {
      error.value = 'Something went wrong'
    }
    productSkuCollection.value = result.map((item: AllProductTypes) => {
      return item.sku
    })
  } catch (exception) {
    error.value = 'Something went wrong'
  }
}

onMounted(() => {
  fetchProductSkuList();
})

const handleSubmit = (event: Event) => {
  validationErrors.value = {};
  const formData = new FormData(event.target as HTMLFormElement);
  const values: Record<string, string> = {};

  values.sku = formData.get('sku') as string;
  values.name = formData.get('name') as string;
  values.price = formData.get('price') as string;
  values.type = formData.get('productType') as string;
  values.weight = formData.get('weight') as string;
  values.size = formData.get('size') as string;
  values.width = formData.get('width') as string;
  values.length = formData.get('length') as string;
  values.height = formData.get('height') as string;

  const handleFunctions: Record<string, HandleFunction> = {
    'Book': handleBookSubmition,
    'DVD': handleDvdSubmition,
    'Furniture': handleFurnitureSubmition
  };

  const handler = handleFunctions[values.type];

  if (handler) {
    const { errors, product } = handler(values, productSkuCollection.value);
    if (!product) {
      validationErrors.value = errors;
    } else {
      handleSave(product);
    }
  }
}

const handleSave = async (product: Partial<AllProductTypes>) => {
  try {
    const response = await fetch('/api/products', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(product),
    });

    if (!response.ok) {
      error.value = 'Failed to save products'
    }
    window.location.href = '/';
  } catch (exception) {
    error.value = 'Failed to save products'
  }
};
</script>

<style scoped>
.product-form-container {
  width: 500px;
  margin: 0 auto;
  background: lightgrey;
  border: solid 1px black;
  border-radius: 10px;
  box-shadow: 5px 5px 10px black;
  padding: 30px;
}

#product_form {
  display: flex;
  flex-direction: column;
}

.form-input-container {
  display: flex;
  flex-direction: row;
  align-items: center;
  margin: 10px 0;

}

.multi-input-container {
  display: flex;
  flex-direction: column;
}

label {
  flex: 0 0 120px;
  font-weight: bold;
}

select,
input {
  padding: 5px;
  border: 1px solid #ccc;
  border-radius: 4px;
  flex: 1;
}

p {
  margin: 0 0 5px 0;
  color: rgb(255, 0, 0);
  font-size: small;
}

@media (max-width: 580px) {
  .product-form-container {
    width: 300px;
    padding: 10px;
  }
}

@media (max-width: 350px) {
  .product-form-container {
    width: 280px;
  }

  .form-input-container {
    flex-direction: column;
  }

  label {
    flex: auto;
  }
}
</style>
