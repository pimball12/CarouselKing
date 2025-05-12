import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useAuthStore = defineStore('auth', () => {

    const token = ref('');
    const user = ref(null);

    function setAuthData(data) {

        token.value = data.token;
        user.value = data.user;
    }

    function clearAuthData() {
        
        token.value = '';
        user.value = null;
    }

    function isAuthenticated() {
        
        return !!token.value;
    }

    return { token, user, setAuthData, clearAuthData, isAuthenticated };
}, {
    
    persist: {
        
        key: 'auth',
        storage: sessionStorage,
    }
});
