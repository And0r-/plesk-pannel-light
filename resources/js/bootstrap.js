// resources/js/bootstrap.js (oder wo du dein axios einbindest)

import axios from 'axios';

// Verwende Cookies für sessionbasierte Authentifizierung
axios.defaults.withCredentials = true;
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Interceptiere Responses, um CSRF- und Session-Probleme automatisch zu behandeln
axios.interceptors.response.use(
    response => response,
    async (error) => {
        if (error.response && error.response.status === 419) {
            // Versuche, den CSRF-Token zu erneuern, und sende den Request erneut
            try {
                await axios.get('/sanctum/csrf-cookie');
                return axios(error.config);
            } catch (refreshError) {
                return Promise.reject(refreshError);
            }
        } else if (error.response && error.response.status === 401) {
            // Session ist ungültig, leite zum Login
            console.log("redirect to login");
            // Inertia.visit('/login');
            window.location.href = '/login';
            return Promise.reject(error);
        }

        return Promise.reject(error);
    }
);

export default axios;
