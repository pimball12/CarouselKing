<script setup>
import axios from 'axios';
import { ref, watch, onMounted } from 'vue'
import { useRoute } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const route = useRoute();
const auth = useAuthStore();
const API_URL = import.meta.env.VITE_API_URL;

const carousel = ref({});
const items = ref([]);

const currentIndex = ref(0)
const transitioning = ref(false)
let timer = null

const getTransitionClass = () => {

    switch (carousel.value.transition_type) {

        case 'F':
            return 'transition-opacity duration-1000 ease-in-out'

        case 'Z':
            return 'transition-transform duration-1000 ease-in-out scale-95 hover:scale-100'

        default:
            return 'transition-transform duration-1000 ease-in-out translate-x-0'
    }
}

const nextSlide = () => {

    if (items.value.length <= 1) return
    currentIndex.value = (currentIndex.value + 1) % items.value.length
}

const playVideoAndWait = (videoEl) => {

    return new Promise((resolve) => {

        videoEl.play()
        videoEl.onended = resolve
    })
}

const startCarousel = async () => {

    clearInterval(timer)

    while (true) {

        const item = items.value[currentIndex.value]

        await new Promise(async (resolve) => {

            transitioning.value = true

            setTimeout(() => {

                transitioning.value = false
            }, 1000)

            if (item.video_url) {

                await playVideoAndWait(document.getElementById(`video-${item.id}`))
                resolve()
            } else {

                setTimeout(resolve, carousel.value.transition_duration * 1000)
            }
        })

        nextSlide()
    }
}

onMounted(async () => {

    let response;

    response = await axios.get(`${API_URL}/carousels/read/${route.params.id}`, {

        headers: { Authorization: `Bearer ${auth.token}` }
    });

    carousel.value = response.data.data;

    response = await axios.get(`${API_URL}/carousel-items/list-by-carousel/${route.params.id}`, {

        headers: { Authorization: `Bearer ${auth.token}` }
    });

    items.value = response.data.data;

    console.log(carousel.value, items.value);

    if (carousel.value && items.value.length)
        startCarousel()
})
</script>

<template>
    <div class="relative w-full h-screen overflow-hidden bg-gray-900 text-gray-100 rounded-lg">
        <div v-for="(item, index) in items" :key="item.id" class="absolute inset-0 transition-opacity duration-1000"
            :class="[getTransitionClass(), {
                'opacity-0 z-0 pointer-events-none': index !== currentIndex,
                'opacity-100 z-10': index === currentIndex,
            }]">
            <a v-if="item.link_url" :href="item.link_url" target="_blank" class="block w-full h-full relative">
                <component :is="item.video_url ? 'video' : 'img'" :id="item.video_url ? `video-${item.id}` : null"
                    :src="item.video_url ? `${API_URL}/file/retrieve/${item.video_url}` : `${API_URL}/file/retrieve/${item.image_url}`"
                    class="w-full h-full object-cover"
                    v-bind="item.video_url ? { muted: true, playsinline: true } : {}" />
                <div class="absolute bottom-0 left-0 bg-black/60 w-full p-4">
                    <h2 class="text-lg font-semibold">{{ item.title }}</h2>
                    <p class="text-sm">{{ item.description }}</p>
                </div>
            </a>
            <div v-else class="w-full h-full relative">
                <component :is="item.video_url ? 'video' : 'img'" :id="item.video_url ? `video-${item.id}` : null"
                    :src="item.video_url ? `${API_URL}/file/retrieve/${item.video_url}` : `${API_URL}/file/retrieve/${item.image_url}`"
                    class="w-full h-full object-cover"
                    v-bind="item.video_url ? { muted: true, playsinline: true } : {}" />
                <div class="absolute bottom-0 left-0 bg-black/60 w-full p-4">
                    <h2 class="text-lg font-semibold">{{ item.title }}</h2>
                    <p class="text-sm">{{ item.description }}</p>
                </div>
            </div>
        </div>
    </div>
</template>