<script setup>

import { onMounted, ref, watch } from 'vue';
import { useAuthStore } from '../stores/auth';
import axios from 'axios';
import { useRoute } from 'vue-router';

const API_URL = import.meta.env.VITE_API_URL;
const route = useRoute();
const auth = useAuthStore();
const emit = defineEmits(['close', 'save']);
const carouselId = defineModel('carouselId');

const form = ref({

    name: '',
    title: '',
    description: '',
    status: 'A',
    transition_type: 'S',
    transition_duration: 5,
});

const itens = ref([]);

const submit = async () => {

    try {

        if (carouselId.value === 0) {

            const response = await axios.post(`${API_URL}/carousels/create`, {company_id: route.params.id, ...form.value}, {

                headers: { Authorization: `Bearer ${auth.token}` }
            });

            emit('save', response.data.data);
        } else {

            const response = await axios.put(`${API_URL}/carousels/update/${carouselId.value}`, form.value, {

                headers: { Authorization: `Bearer ${auth.token}` }
            });

            emit('save', response.data.data);
        }
    } catch (error) {

        console.error('Error saving carousel:', error);
    }
}

const close = () => {

    emit('close');
}

onMounted(() => {

    if (carouselId.value !== 0) {

        axios.get(`${import.meta.env.VITE_API_URL}/carousels/read/${carouselId.value}`, {

            headers: { Authorization: `Bearer ${auth.token}` } 
        }).then(({ data }) => {

            form.value = data.data;
            // itens.value = data.data.itens;
        }).catch(err => {

            console.error('Error fetching carousel', err);
        });
    }
});

</script>


<template>
    <div class="fixed inset-0 flex justify-center items-center z-50" style="background-color: rgba(1, 1, 1, 0.5);">
        <div class="bg-white w-full max-w-3xl p-6 rounded shadow-lg max-h-screen overflow-y-auto relative">
            <h2 class="text-xl font-bold mb-4">Novo Carousel</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Nome</label>
                    <input v-model="form.name" class="w-full border rounded px-2 py-1" />
                </div>

                <div>
                    <label class="block text-sm font-medium">Título</label>
                    <input v-model="form.title" class="w-full border rounded px-2 py-1" />
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium">Descrição</label>
                    <textarea v-model="form.description" class="w-full border rounded px-2 py-1"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium">Transição</label>
                    <select v-model="form.transition_type" class="w-full border rounded px-2 py-1">
                        <option value="S">Slide</option>
                        <option value="F">Fade</option>
                        <option value="Z">Zoom</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium">Duração</label>
                    <input type="number" v-model="form.transition_duration"
                        class="w-full border rounded px-2 py-1" />
                </div>

                <div>
                    <label class="block text-sm font-medium">Status</label>
                    <select v-model="form.status" class="w-full border rounded px-2 py-1">
                        <option value="A">Active</option>
                        <option value="I">Inactive</option>
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium">CSS Personalizado</label>
                    <textarea v-model="form.custom_css" class="w-full border rounded px-2 py-1"></textarea>
                </div>
            </div>

            <!-- Itens -->
            <h3 class="text-lg font-bold mt-6 mb-2">Itens do Carousel</h3>

            <div v-for="(item, index) in form.itens" :key="index" class="border p-3 rounded mb-2">
                <div class="flex justify-between mb-2">
                    <strong>Item {{ index + 1 }}</strong>
                    <button @click="removeItem(index)" class="text-red-500 hover:text-red-700">Remover</button>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <input v-model="item.title" placeholder="Título" class="border rounded px-2 py-1" />
                    <input v-model="item.description" placeholder="Descrição" class="border rounded px-2 py-1" />
                    <input v-model="item.image_url" placeholder="Imagem URL" class="border rounded px-2 py-1" />
                    <input v-model="item.video_url" placeholder="Vídeo URL" class="border rounded px-2 py-1" />
                    <input v-model="item.link_url" placeholder="Link URL" class="border rounded px-2 py-1" />
                    <input v-model="item.order" type="number" placeholder="Ordem" class="border rounded px-2 py-1" />
                    <select v-model="item.status" class="border rounded px-2 py-1">
                        <option value="A">Ativo</option>
                        <option value="I">Inativo</option>
                    </select>
                </div>
            </div>

            <button @click="addItem"
                class="mt-2 mb-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Adicionar Item</button>

            <div class="flex justify-end gap-2 mt-4">
                <button @click="close"
                    class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded cursor-pointer">Cancelar</button>
                <button @click="submit"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 cursor-pointer">Salvar</button>
            </div>
        </div>
    </div>
</template>