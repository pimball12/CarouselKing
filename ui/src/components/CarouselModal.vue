<script setup>

import { onMounted, ref, watch } from 'vue';
import { useAuthStore } from '../stores/auth';
import axios from 'axios';
import { useRoute } from 'vue-router';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const API_URL = import.meta.env.VITE_API_URL;
const route = useRoute();
const auth = useAuthStore();
const emit = defineEmits(['close', 'modalSave']);
const carouselId = defineModel('carouselId');
const addMode = ref(false);

const form = ref({

    name: '',
    title: '',
    description: '',
    status: 'A',
    transition_type: 'S',
    transition_duration: 5,
});

const initialItem = {

    title: '',
    description: '',
    image_url: '',
    video_url: '',
    link_url: '',
    order: 0,
    status: 'A',
}
const itemForm = ref({ ...initialItem });

const items = ref([]);

const submit = async () => {

    try {

        if (carouselId.value === 0) {

            const response = await axios.post(`${API_URL}/carousels/create`, { company_id: route.params.id, ...form.value }, {

                headers: { Authorization: `Bearer ${auth.token}` }
            });

            emit('modalSave', response.data.data);
        } else {

            const response = await axios.put(`${API_URL}/carousels/update/${carouselId.value}`, form.value, {

                headers: { Authorization: `Bearer ${auth.token}` }
            });

            emit('modalSave', response.data.data);
        }
    } catch (error) {

        console.error('Error saving carousel:', error);
    }
}

const submitItem = async () => {

    try {

        const response = await axios.post(`${API_URL}/carousel-items/create`, {

            carousel_id: carouselId.value,
            ...itemForm.value,
            order: parseInt(itemForm.value.order)
        }, {

            headers: { Authorization: `Bearer ${auth.token}` }
        });

        items.value.push(response.data.data);
        itemForm.value = { ...initialItem };

    } catch (error) {

        console.error('Error saving item:', error);
    }
}

const deleteItem = async (id) => {

    try {

        await axios.delete(`${API_URL}/carousel-items/delete`, {

            headers: { Authorization: `Bearer ${auth.token}` },
            data: { id }
        });

        items.value = items.value.filter(c => c.id !== id);
    } catch (error) {

        console.error('Error deleting item:', error);
    } finally {

        loading.value = false;
    }
};

const close = () => {

    emit('close');
}

async function handleFileUpload(event, field) {

    const file = event.target.files[0]
    if (!file) return

    const formData = new FormData()
    formData.append('file', file)

    try {

        const response = await axios.post(API_URL + '/file/upload', formData, {

            headers: {

                Authorization: `Bearer ${auth.token}`,
                'Content-Type': 'multipart/form-data'
            }
        })

        itemForm.value[field] = response.data.data.name;

        console.log(itemForm.value.image_url);
    } catch (error) {

        console.error(`Erro ao enviar arquivo (${field}):`, error)
    }
}


onMounted(async () => {

    if (carouselId.value !== 0) {

        let response;

        response = await axios.get(`${API_URL}/carousels/read/${carouselId.value}`, {

            headers: { Authorization: `Bearer ${auth.token}` }
        })
        
        form.value = response.data.data;

        response = await axios.get(`${API_URL}/carousel-items/list-by-carousel/${carouselId.value}`, {

            headers: { Authorization: `Bearer ${auth.token}` }
        })
        
        items.value = response.data.data;
    }
});

</script>


<template>
    <div class="fixed inset-0 flex justify-center items-center z-50" style="background-color: rgba(1, 1, 1, 0.5);">
        <div class="bg-white w-full max-w-4xl p-6 rounded shadow-lg max-h-screen overflow-y-auto relative">
            <h2 class="text-xl font-bold mb-4">Carousel #{{ carouselId != 0 ? carouselId : 'New' }}</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Name</label>
                    <input v-model="form.name" class="w-full border rounded px-2 py-1" />
                </div>

                <div>
                    <label class="block text-sm font-medium">Title</label>
                    <input v-model="form.title" class="w-full border rounded px-2 py-1" />
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium">Description</label>
                    <textarea v-model="form.description" class="w-full border rounded px-2 py-1"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium">Transition</label>
                    <select v-model="form.transition_type" class="w-full border rounded px-2 py-1">
                        <option value="S">Slide</option>
                        <option value="F">Fade</option>
                        <option value="Z">Zoom</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium">Duration</label>
                    <input type="number" v-model="form.transition_duration" class="w-full border rounded px-2 py-1" />
                </div>

                <div>
                    <label class="block text-sm font-medium">Status</label>
                    <select v-model="form.status" class="w-full border rounded px-2 py-1">
                        <option value="A">Active</option>
                        <option value="I">Inactive</option>
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium">Custom CSS</label>
                    <textarea v-model="form.custom_css" class="w-full border rounded px-2 py-1"></textarea>
                </div>
            </div>

            <template v-if="carouselId !== 0">

                <div class="relative bg-gray-100 my-4 min-h-[60px] flex items-center rounded">
                    <h3 class="text-xl font-semibold absolute left-1/2 transform -translate-x-1/2">
                        Carousel Items
                    </h3>
                    <div class="absolute right-3">
                        <button @click="addMode = !addMode"
                            class="bg-blue-500 hover:bg-blue-600 cursor-pointer text-white text-sm px-4 py-2 rounded shadow">
                            <FontAwesomeIcon v-if="!addMode" icon="plus"></FontAwesomeIcon>
                            <FontAwesomeIcon v-if="addMode" icon="minus"></FontAwesomeIcon>
                        </button>
                    </div>
                </div>

                <div v-show="addMode" class="bg-white rounded mb-6 flex flex-col">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium mb-1">Title</label>
                            <input v-model="itemForm.title" type="text" class="w-full border rounded p-2" required />
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium mb-1">Description</label>
                            <textarea v-model="itemForm.description" class="w-full border rounded px-2 py-1"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Image</label>
                            <input type="file" @change="handleFileUpload($event, 'image_url')"
                                class="w-full border rounded p-2" accept="image/*" />

                            <img v-if="itemForm.image_url" class="w-full"
                                :src="API_URL + '/file/retrieve/' + itemForm.image_url">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Video</label>
                            <input type="file" @change="handleFileUpload($event, 'video_url')"
                                class="w-full border rounded p-2" accept="video/*" />
                        </div>


                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium mb-1">Link</label>
                            <input v-model="itemForm.link_url" type="url" class="w-full border rounded p-2" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Order</label>
                            <input v-model.number="itemForm.order" type="number" class="w-full border rounded p-2"
                                min="0" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Status</label>
                            <select v-model="itemForm.status" class="w-full border rounded p-2">
                                <option value="A">Active</option>
                                <option value="I">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" @click="submitItem()"
                        class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 cursor-pointer">
                        <FontAwesomeIcon icon="save"></FontAwesomeIcon> Add Item
                    </button>
                </div>


                <table class="min-w-full table-auto border-collapse shadow rounded-lg">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="p-2 border text-center">ID</th>
                            <th class="p-2 border">Title</th>
                            <th class="p-2 border">Description</th>
                            <th class="p-2 border">Image</th>
                            <th class="p-2 border">Video</th>
                            <th class="p-2 border">Link</th>
                            <th class="p-2 border">Order</th>
                            <th class="p-2 border">Status</th>
                            <th class="p-2 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in items" :key="item.id">
                            <td class="p-2 border text-center">{{ item.id }}</td>
                            <td class="p-2 border">{{ item.title }}</td>
                            <td class="p-2 border">{{ item.description }}</td>
                            <td class="p-2 border max-w-1"><img v-if="item.image_url" :src="`${API_URL}/file/retrieve/${item.image_url}`" /></td>
                            <td class="p-2 border"> <a v-if="item.video_url" class="text-blue-600" :href="`${API_URL}/file/retrieve/${item.video_url}`">Download</a></td>
                            <td class="p-2 border">{{ item.link_url }}</td>
                            <td class="p-2 border">{{ item.order }}</td>
                            <td class="p-2 border">{{ item.status }}</td>

                            <td class="p-2 border">
                                <div class="flex justify-center gap-4">
                                    <button @click="deleteItem(item.id)" class="text-red-400 cursor-pointer">
                                        <FontAwesomeIcon icon="trash"></FontAwesomeIcon>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="items.length === 0">
                            <td colspan="8" class="p-2 text-center">No items registered.</td>
                        </tr>
                    </tbody>
                </table>
            </template>

            <div class="flex justify-end gap-2 mt-4">
                <button @click="close"
                    class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded cursor-pointer">Cancel</button>
                <button @click="submit"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 cursor-pointer">Save</button>
            </div>
        </div>
    </div>
</template>