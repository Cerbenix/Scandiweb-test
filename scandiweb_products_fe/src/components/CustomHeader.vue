<template>
    <header class="header">
        <slot></slot>
    </header>
</template>

<style scoped>
.header {
    display: flex;
    justify-content: space-between;
    padding: 30px 5% 5px 5%;
    margin-bottom: 20px;
    box-shadow: 0 0 20px black;
    background: darkslategrey;
    font-size: larger;
}

@media (max-width: 520px) {
  .header {
    flex-direction: column;
    padding: 5px 5%;
  }
}


</style>