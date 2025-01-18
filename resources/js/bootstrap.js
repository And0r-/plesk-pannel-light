import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Set CSRF Token from Laravel's cookie
let token = document.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;
axios.defaults.headers.common['Accept'] = 'application/json';