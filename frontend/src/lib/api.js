import axios from 'axios';

const api = axios.create({
    baseURL: 'http://localhost:8000/api',
    withCredentials: true,
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
});

// We need a separate instance for CSRF that hits the root URL (not /api)
export const csrfApi = axios.create({
    baseURL: 'http://localhost:8000',
    withCredentials: true,
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
});

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift());
}

// Manually attach X-XSRF-TOKEN for cross-origin requests (port difference)
api.interceptors.request.use((config) => {
    const token = getCookie('XSRF-TOKEN');
    if (token) {
        config.headers['X-XSRF-TOKEN'] = token;
    }
    return config;
});

// Global Response Interceptor to handle Session Expiry
api.interceptors.response.use(
    (response) => response,
    (error) => {
        // If the server returns 401 Unauthorized (e.g. session expired)
        if (error.response && error.response.status === 401) {
            // Clear any local user data
            localStorage.removeItem('user');
            // If we are not already on the login page, redirect
            if (window.location.pathname !== '/login') {
                window.location.href = '/login';
            }
        }
        return Promise.reject(error);
    }
);

export default api;
