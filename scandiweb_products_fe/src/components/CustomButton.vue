<template>
  <button @click="handleClick" :type="props.type"
    class="button">
    <slot></slot>
  </button>
</template>
  
<script setup lang="ts">
const props = withDefaults(
  defineProps<{
    type?: 'button' | 'submit' | 'reset'
  }>(),
  {
    type: 'button'
  }
)

const emit = defineEmits<{
  (e: 'customClick'): void
}>()

const handleClick = () => {
  emit('customClick')
}
</script>

<style>
.button{
  font: inherit;
  background: white;
  border: solid 2px black;
  border-radius: 5px;
  box-shadow: 2px 2px 5px rgb(54, 54, 54);
  padding: 5px 8px;
  margin: 5px;
  cursor: pointer;
  transition: all 200ms;
}
.button:hover{
  background: lightsalmon;
}
</style>