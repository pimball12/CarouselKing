<script setup>
import { useField, useForm } from 'vee-validate';
import { object, string } from 'yup';
import axios from 'axios';
import { useAuthStore } from '../stores/auth';
import { onMounted, ref } from 'vue';
import router from '../router';

const auth = useAuthStore();

const schema = object({
    
    username: string().email('E-mail inválido').required('E-mail é obrigatório'),
    password: string().required('Senha é obrigatória')
});

const { handleSubmit, errors } = useForm({

    validationSchema: schema
});

const { value: username } = useField('username');
const { value: password } = useField('password');

const errorMessage = ref('');

const onSubmit = handleSubmit(async (values) => {

    try {

        const response = await axios.post(import.meta.env.VITE_API_URL +  '/auth/login', {

            email: values.username,
            password: values.password
        });

        if (response.data.success) {

            console.log(response.data.data, 'lol');
            auth.setAuthData(response.data.data);
            errorMessage.value = '';
            router.push({ name: 'home' });
        } else {

            errorMessage.value = response.data.message || 'Error';
        }
    } catch (err) {

        console.log(err);
        errorMessage.value = err.response?.data?.message || 'Connection error or invalid credentials.';
    }
});


onMounted(() => {

    if (auth.isAuthenticated()) {

        router.push({ name: 'home' });
    }
});

</script>

<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
            <div class="mb-4">
                <img src="../assets/icon.png" alt="Logo" class="mx-auto w-50" />
                <h2 class="text-center text-2xl"><i>Carousel King</i></h2>
            </div>
            <form @submit.prevent="onSubmit" class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">E-mail</label>
                    <input v-model="username" type="text" id="username"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                    <p v-if="errors.username" class="text-red-500 text-xs mt-1">{{ errors.username }}</p>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input v-model="password" type="password" id="password"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                    <p v-if="errors.password" class="text-red-500 text-xs mt-1">{{ errors.password }}</p>
                </div>
                <div class="flex items-center justify-between">
                    <div class="text-sm">
                        <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Forgot your password?</a>
                    </div>
                </div>
                <button type="submit"
                    class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 cursor-pointer">
                    Sign in
                </button>
            </form>
            <p v-if="errorMessage" class="text-center text-sm text-red-600">{{ errorMessage }}</p>
        </div>
    </div>
</template>

<style scoped></style>
