import axios from 'axios';
import getenv from "@/libs/getenv";
import store from "@/store";

const api = axios.create({
    baseURL: `${getenv.vars.apiBaseUrl}/api/v1`,
    withCredentials: false,
});

const getApiHeaders = () => {
    let headers = {
        'X-Requested-With': 'XMLHttpRequest',
        Accept: 'application/json',
        'Content-Type': 'application/json'
    }
    let token = store.getters['auth/getToken'];

    if (store.getters['auth/getUser']) {
        headers.Authorization = `Bearer ${token}`;
    }

    return headers;
}

const axiosPost = async (url, body, onSuccess, onError) => {
    await api.post(url, body, {
        crossDomain: true,
        headers: getApiHeaders()
    }).then(response => onSuccess(response))
        .catch(error => typeof onError === 'function' && onError(error.response))
};

const apis = {
    auth(body, onSuccess, onError = null) {
        return axiosPost('auth/login', body, onSuccess, onError)
    },
    logout(onSuccess, onError = null) {
        return axiosPost('auth/logout', {}, onSuccess, onError);
    }
};

export default {
    install(app) {
        app.config.globalProperties.$api = apis;
    }
};
