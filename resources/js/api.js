import axios from 'axios';

const $api = axios.create({
    withCredentials: true,
});

$api.interceptors.request.use((config) => {
    config.headers['Content-Type'] = 'application/json';
    return config;
});

export default $api;
