<template>
  <article class="product-card">
    <input type="checkbox" v-model="isChecked" @change="handleCheckboxChange" class="delete-checkbox"/>
    <p>{{ productInfo.sku }}</p>
    <p>{{ productInfo.name }}</p>
    <p>{{ productInfo.price.toFixed(2) }} $</p>
    <template v-for="(value, key) in productInfo" :key="key">
      <p v-if="isUniqueProperty(key)">
        {{ capitalizeFirstLetter(key) }}: {{ value }}
      </p>
    </template>
  </article>
</template>

<script setup lang="ts">
import { ref } from "vue";
import type { Product } from "@/views/ProductView.vue";

const props = defineProps<{
  productInfo: Product;
}>();

const emit = defineEmits<(e: 'checkbox-change', id: number, isChecked: boolean) => void>();

const isChecked = ref(false);

const handleCheckboxChange = (event: Event) => {
  isChecked.value = (event.target as HTMLInputElement).checked;
  emit('checkbox-change', props.productInfo.id, isChecked.value);
};

const isUniqueProperty = (key: any) => {
  return key !== "id" && key !== "sku" && key !== "name" && key !== "price" && key !== 'type';
};

const capitalizeFirstLetter = (key: keyof Product) => {
  const capitalizedKey = key.charAt(0).toUpperCase() + key.slice(1);
  return capitalizedKey.toString();
};
</script>

<style scoped>
.product-card {
  display: flex;
  flex-direction: column;
  justify-content: center;
  text-align: center;
  border: solid 1px black;
  border-radius: 10px;
  background: lightsalmon;
  box-shadow: 5px 5px 10px black;
  color: black;
  font-size: larger;
  font-weight: bold;
  padding: 10px;
  height: 200px;
  position: relative;
}

p {
  margin: 5px 0;
}

.delete-checkbox{
    position: absolute;
    top: 5%;
    left: 5%;
}
</style>
