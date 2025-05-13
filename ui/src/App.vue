<script setup lang="ts">

import router from './router';

</script>

<template>

  <router-link v-if="router.currentRoute.value.name !== 'login'" to="/logout"
    class="fixed top-4 right-4 px-4 py-2 bg-red-600 text-white rounded shadow hover:bg-red-700 font-semibold z-50">
    Logout
  </router-link>

  <router-view></router-view>

</template>

<style scoped></style>
