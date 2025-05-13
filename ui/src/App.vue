<script setup lang="ts">

import router from './router';

</script>

<template>

	<header v-if="router.currentRoute.value.name !== 'login' && router.currentRoute.value.name !== 'carousel-view'"
		class="bg-gray-400 text-white p-4 flex justify-between items-center">
		<div class="text-lg font-semibold">
			<a href="/" class="hover:underline">Home</a>
		</div>
		
		<router-link v-if="router.currentRoute.value.name !== 'login'" to="/logout"
			class="text-white px-4 py-2 rounded">
			Logout
		</router-link>
	</header>


	<router-view></router-view>

</template>

<style scoped></style>
