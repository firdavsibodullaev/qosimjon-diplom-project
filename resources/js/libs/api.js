import axios from 'axios';
import getenv from "@/libs/getenv";
import store from "@/store";
import toastr from "toastr";
import router from "@/libs/router";
import makeQuery from "@/utils/makeQuery";

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

    if (store.getters['auth/isAuthenticated']) {
        headers.Authorization = `Bearer ${token}`;
    }

    return headers;
}

const axiosGet = async (url, onSuccess, onError) => {
    await api.get(url, {
        crossDomain: true,
        headers: getApiHeaders()
    }).then(response => onSuccess(response))
        .catch(error => errorHandler(error, onError))
};

const axiosPost = async (url, body, onSuccess, onError) => {
    await api.post(url, body, {
        crossDomain: true,
        headers: getApiHeaders()
    }).then(response => onSuccess(response))
        .catch(error => {
            errorHandler(error, onError);
        })
};

const axiosPut = async (url, body, onSuccess, onError) => {
    await api.put(url, body, {
        crossDomain: true,
        headers: getApiHeaders()
    }).then(response => onSuccess(response))
        .catch(error => {
            errorHandler(error, onError);
        })
};

const axiosDelete = async (url, onSuccess, onError) => {
    await api.delete(url, {
        crossDomain: true,
        headers: getApiHeaders()
    }).then(response => onSuccess(response))
        .catch(error => errorHandler(error, onError))
};

const errorHandler = (error, onError) => {
    let status = error?.response?.status;
    if (status === 500) {
        toastr.error('Whoops! Server Hatoligi ro\'y berdi')
    }

    if (status === 401) {
        if (error.config.url !== 'auth/login') {
            store.dispatch('auth/clearAll');
            router.push({name: 'login'})
            return;

        }
    }

    return typeof onError === 'function' && onError(error);
}


const apis = {
    // Авторизация
    auth(body, onSuccess, onError = null) {
        return axiosPost('auth/login', body, onSuccess, onError)
    },
    logout(onSuccess, onError = null) {
        return axiosPost('auth/logout', {}, onSuccess, onError);
    },

    // Заводы
    getFactories(filters, onSuccess, onError = null) {
        return axiosGet(`admin/factory?${makeQuery(filters)}`, onSuccess, onError);
    },
    getFactory(id, onSuccess, onError = null) {
        return axiosGet(`admin/factory/${id}`, onSuccess, onError);
    },
    createFactory(data, onSuccess, onError = null) {
        return axiosPost(`admin/factory`, data, onSuccess, onError);
    },
    updateFactory(id, data, onSuccess, onError = null) {
        return axiosPut(`admin/factory/${id}`, data, onSuccess, onError);
    },
    deleteFactory(id, onSuccess, onError = null) {
        return axiosDelete(`admin/factory/${id}`, onSuccess, onError);
    },

    // Цехи
    getFactoryFloors(filters, onSuccess, onError = null) {
        return axiosGet(`admin/factory-floor?${makeQuery(filters)}`, onSuccess, onError);
    },
    getFactoryFloor(id, onSuccess, onError = null) {
        return axiosGet(`admin/factory-floor/${id}`, onSuccess, onError);
    },
    createFactoryFloor(data, onSuccess, onError = null) {
        return axiosPost(`admin/factory-floor`, data, onSuccess, onError);
    },
    updateFactoryFloor(id, data, onSuccess, onError = null) {
        return axiosPut(`admin/factory-floor/${id}`, data, onSuccess, onError);
    },
    deleteFactoryFloor(id, onSuccess, onError = null) {
        return axiosDelete(`admin/factory-floor/${id}`, onSuccess, onError);
    }
};

export default {
    apis,
    install(app) {
        app.config.globalProperties.$api = apis;
    }
};
