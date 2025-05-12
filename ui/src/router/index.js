import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/Home.vue';
import Login from '../components/Login.vue';

const routes = [

    {
        path: '/',
        name: 'hello-world',
        component: Home
    },
    {
        path: '/login',
        name: 'Login',
        component: Login
    }
];

const router = createRouter({

    history: createWebHistory(),
    routes
});


router.beforeEach((to, from, next) => {

    const isAuthenticated = localStorage.getItem('token') !== null;

    if (to.name !== 'Login' && !isAuthenticated) {

        next({ name: 'Login' });
    } else {

        next();
    }
});

export default router;