import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/Home.vue';
import Login from '../components/Login.vue';
import { useAuthStore } from '../stores/auth';
import CarouselView from '../components/CarouselView.vue';

const routes = [

    {
        path: '/',
        name: 'home',
        component: Home
    },
    {
        path: '/login',
        name: 'login',
        component: Login
    },
    {
        path: '/logout',
        name: 'logout'
    },
    {
        path: '/company/:id',
        name: 'company',
        component: () => import('../components/Company.vue'),
    },
    {
        path: '/carousel/view/:id',
        name: 'carousel-view',
        component: CarouselView
    }
];

const router = createRouter({

    history: createWebHistory(),
    routes
});


router.beforeEach((to, from, next) => {

    const authStore = useAuthStore();
    const isAuthenticated = authStore.isAuthenticated();

    if (to.name === 'logout') {
     
        console.log('Logging out...');
        authStore.clearAuthData();
        next({ name: 'login' });
    }

    if (to.name !== 'login' && !isAuthenticated) {

        next({ name: 'login' });
    } else {

        next();
    }
});

export default router;