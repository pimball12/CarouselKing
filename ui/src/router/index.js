import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/Home.vue';
import Login from '../components/Login.vue';
import { useAuthStore } from '../stores/auth';

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
    }
];

const router = createRouter({

    history: createWebHistory(),
    routes
});


router.beforeEach((to, from, next) => {

    const authStore = useAuthStore();
    const isAuthenticated = authStore.isAuthenticated();

    if (to.name !== 'login' && !isAuthenticated) {

        next({ name: 'login' });
    } else {

        next();
    }
});

export default router;