<script setup>

import { ref, onMounted } from 'vue';
import { useAuthStore } from '../stores/auth';
import axios from 'axios';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import router from '../router';

const API_URL = import.meta.env.VITE_API_URL;

const auth = useAuthStore();

const companies = ref([]);
const loading = ref(true);

const form = ref({
    name: '',
    doc: '',
    address: ''
});

const fetchCompanies = async () => {

    try {

        const response = await axios.get(`${API_URL}/companies/list`, {

            headers: { Authorization: `Bearer ${auth.token}` }
        });

        companies.value = response.data.data;
    } catch (error) {

        console.error('Erro ao buscar empresas:', error);
    } finally {

        loading.value = false;
    }
};

const submitCompany = async () => {

    try {

        const response = await axios.post(`${API_URL}/companies/create`, form.value, {

            headers: { Authorization: `Bearer ${auth.token}` }
        });

        companies.value.push(response.data.data);
        form.value = { name: '', doc: '', address: '' };

    } catch (error) {

        console.error('Erro ao cadastrar empresa:', error);
    }
};

const deleteCompany = async (id) => {

    try {

        await axios.delete(`${API_URL}/companies/delete`, {

            headers: { Authorization: `Bearer ${auth.token}` },
            data: { id }
        });

        companies.value = companies.value.filter(c => c.id !== id);
    } catch (error) {

        console.error('Erro ao excluir empresa:', error);
    }
};

onMounted(() => {

    fetchCompanies();
});

</script>

<template>

    <div class="flex justify-center bg-gray-100 p-[30px] min-h-screen">
        <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-5xl">
            <h1 class="text-2xl font-semibold mb-4">Companies</h1>

            <!-- Cadastro de Empresa -->
            <form @submit.prevent="submitCompany" class="bg-white rounded mb-6 flex flex-col">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Name</label>
                        <input v-model="form.name" type="text" class="w-full border rounded p-2" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Doc</label>
                        <input v-model="form.doc" type="text" class="w-full border rounded p-2" required />
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Address</label>
                        <input v-model="form.address" type="text" class="w-full border rounded p-2" />
                    </div>
                </div>
                <button type="submit"
                    class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 cursor-pointer">
                    <FontAwesomeIcon icon="save"></FontAwesomeIcon> Register
                </button>
            </form>

            <!-- Listagem de Empresas -->
            <div v-if="loading" class="text-gray-500">Carregando empresas...</div>
            <div v-else class="overflow-auto max-h-[350px]">
                <table class="min-w-full table-auto border-collapse shadow rounded-lg">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="p-2 border text-center">ID</th>
                            <th class="p-2 border">Name</th>
                            <th class="p-2 border">Doc</th>
                            <th class="p-2 border">Address</th>
                            <th class="p-2 border text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="company in companies" :key="company.id">
                            <td class="p-2 border text-center">{{ company.id }}</td>
                            <td class="p-2 border">{{ company.name }}</td>
                            <td class="p-2 border">{{ company.doc }}</td>
                            <td class="p-2 border">{{ company.address }}</td>
                            <td class="p-2 border">
                                <div class="flex justify-center gap-4">
                                    <button @click="deleteCompany(company.id)" class="text-red-400 cursor-pointer">
                                        <FontAwesomeIcon icon="trash"></FontAwesomeIcon>
                                    </button>
                                    <button @click="() => router.push({name: 'company', params: {id: company.id}})" class="text-blue-400 cursor-pointer">
                                        <FontAwesomeIcon icon="search"></FontAwesomeIcon>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="companies.length === 0">
                            <td colspan="5" class="p-2 text-center">No companies registered.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</template>

<style scoped>
table th,
table td {
    font-size: 0.875rem;
}
</style>