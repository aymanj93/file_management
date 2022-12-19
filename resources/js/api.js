import axios from 'axios';

const $api = axios.create({
    withCredentials: true,
});

$api.interceptors.request.use((config) => {
    config.headers['Content-Type'] = 'application/json';
    config.headers['Content-Type'] = 'multipart/form-data';
    return config;
});

export default $api;
