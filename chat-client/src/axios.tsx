const axios = require('axios');

export const endpoints = {
    'new': '/chat',
    detail: (code: string) => `/chat/${code}`,
    history: (code: string) => `/chat/${code}/messages`,
    members: (code: string) => `/chat/${code}/members`,
};

axios.defaults.baseURL = process.env.REACT_APP_API_URL;

export default axios;
