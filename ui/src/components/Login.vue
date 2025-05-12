<script setup>
import { ref } from 'vue';

const username = ref('');
const password = ref('');
const errorMessage = ref('');

const handleLogin = () => {

    if (username.value === 'admin' && password.value === '1234') {
        errorMessage.value = '';
        
        window.location.href = '/';
    } else {
        errorMessage.value = 'Usuário ou senha inválidos!';
    }
};
</script>

<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
            <div class="mb-4">
                <img src="../assets/icon.png" alt="Logo" class="mx-auto w-50"  />
                <h2 class="text-center text-2xl"><i>Carousel King</i></h2>
                <!-- <hr class="mt-5 mb-1"></hr> -->
            </div>
            <form @submit.prevent="handleLogin" class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">E-mail</label>
                    <input v-model="username" type="text" id="username" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input v-model="password" type="password" id="password" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                </div>
                <div class="flex items-center justify-between">
                    <div class="text-sm">
                        <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Forgot your password?</a>
                    </div>
                </div>
                <button type="submit"
                    class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Sign in
                </button>
            </form>
            <p v-if="errorMessage" class="text-center text-sm text-red-600">{{ errorMessage }}</p>
        </div>
    </div>
</template>

<style scoped></style>