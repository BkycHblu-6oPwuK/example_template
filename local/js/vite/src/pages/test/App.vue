<script setup>
import HelloWorld from './components/HelloWorld.vue'
import img1 from '@/assets/images/11.png';
import img2 from '/public/images/11.png';
</script>

<template>
  <HelloWorld msg="Vite + Vue1" />
  <div><img :src="img1" alt=""></div>
  <div><img :src="img2" alt=""></div>
</template>

<style scoped>
.logo {
  height: 6em;
  padding: 1.5em;
  will-change: filter;
}
.logo:hover {
  filter: drop-shadow(0 0 2em #646cffaa);
}
.logo.vue:hover {
  filter: drop-shadow(0 0 2em #42b883aa);
}
</style>
