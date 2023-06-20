<template>
    <a class="link">
        <slot></slot>
    </a>
</template>

<style>
.link {
  text-decoration: none;
  color: black;
  font: inherit;
  margin: 5px;
  background: white;
  border: solid 2px black;
  border-radius: 5px;
  box-shadow: 2px 2px 5px rgb(54, 54, 54);
  padding: 5px 8px;
  margin: 5px;
  cursor: pointer;
  transition: all 200ms;
}

.link:hover {
background: lightsalmon;
}
</style>