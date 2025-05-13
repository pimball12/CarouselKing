<script setup>

import axios from 'axios';
import { onMounted, ref } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useRoute } from 'vue-router';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { TransitionType, getTransitionTypeLabel } from '../Enums/TransitionType'
import { Status, getStatusLabel } from '../Enums/Status';
import CarouselModal from './CarouselModal.vue';

const API_URL = import.meta.env.VITE_API_URL;
const loading = ref(true);
const auth = useAuthStore();
const route = useRoute();
const company = ref(null);
const carousels = ref([]);

const modalCarouselId = ref(null);

onMounted(async () => {

    try {

        let response;

        response = await axios.get(`${API_URL}/companies/read/${route.params.id}`, {

            headers: { Authorization: `Bearer ${auth.token}` }
        });

        company.value = response.data.data;

        response = await axios.get(`${API_URL}/carousels/list-by-company/${route.params.id}`, {

            headers: { Authorization: `Bearer ${auth.token}` }
        });

        carousels.value = response.data.data;
    } catch (error) {

        console.error('Error retrieving company:', error);
    } finally {

        loading.value = false;
    }
});

const deleteCarousel = async (id) => {

    loading.value = true;

    try {

        await axios.delete(`${API_URL}/carousels/delete`, {

            headers: { Authorization: `Bearer ${auth.token}` },
            data: { id }
        });

        carousels.value = carousels.value.filter(c => c.id !== id);
    } catch (error) {

        console.error('Error deleting carousel:', error);
    } finally {

        loading.value = false;
    }
};

const modalOpen = (id = 0) =>  {

    modalCarouselId.value = id;
}

const modalSave = (carousel) =>  {

    if (modalCarouselId.value === 0) {

        carousels.value.push(carousel);
        console.log(carousels.value);
    } else {

        const index = carousels.value.findIndex(c => c.id === carousel.id);

        if (index !== -1) {

            carousels.value[index] = carousel;
        }
    }

    modalCarouselId.value = null;
}

</script>

<template>

    <div class="flex justify-center bg-gray-100 p-[30px] min-h-screen">
        <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-5xl">
            <h1 class="font-semibold mb-4">{{ company?.name ?? '' }}</h1>
            <div class="relative mb-4 bg-gray-100 min-h-[60px] flex items-center">
                <h3 class="text-xl font-semibold absolute left-1/2 transform -translate-x-1/2">
                    Carousels
                </h3>
                <div class="absolute right-3">
                    <button @click="modalOpen()"
                        class="bg-green-500 hover:bg-green-600 cursor-pointer text-white text-sm px-4 py-2 rounded shadow">
                        <FontAwesomeIcon icon="plus"></FontAwesomeIcon> Add
                    </button>
                </div>
            </div>

            <div v-if="loading" class="text-gray-500">Loading companies...</div>
            <div v-else>
                <table class="min-w-full table-auto border-collapse shadow rounded-lg">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="p-2 border text-center">ID</th>
                            <th class="p-2 border">Name</th>
                            <th class="p-2 border">Title</th>
                            <th class="p-2 border">Description</th>
                            <th class="p-2 border">Status</th>
                            <th class="p-2 border">Trans. Type</th>
                            <th class="p-2 border">Trans. Duration</th>
                            <th class="p-2 border text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="carousel in carousels" :key="carousel.id">
                            <td class="p-2 border text-center">{{ carousel.id }}</td>
                            <td class="p-2 border">{{ carousel.name }}</td>
                            <td class="p-2 border">{{ carousel.title }}</td>
                            <td class="p-2 border">{{ carousel.description }}</td>
                            <td class="p-2 border">{{ getStatusLabel(carousel.status) }}</td>
                            <td class="p-2 border">{{ getTransitionTypeLabel(carousel.transition_type) }}</td>
                            <td class="p-2 border">{{ carousel.transition_duration }}</td>

                            <td class="p-2 border">
                                <div class="flex justify-center gap-4">
                                    <button @click="deleteCarousel(carousel.id)" class="text-red-400 cursor-pointer">
                                        <FontAwesomeIcon icon="trash"></FontAwesomeIcon>
                                    </button>
                                    <button
                                        @click="modalOpen(carousel.id)"
                                        class="text-blue-400 cursor-pointer">
                                        <FontAwesomeIcon icon="search"></FontAwesomeIcon>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="carousels.length === 0">
                            <td colspan="8" class="p-2 text-center">No carousels registered.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <CarouselModal v-if="modalCarouselId !== null" v-model:carousel-id="modalCarouselId" @close="modalCarouselId = null" @save="modalSave" />

</template>