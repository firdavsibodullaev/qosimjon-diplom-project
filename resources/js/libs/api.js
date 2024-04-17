import axios from 'axios';
import getenv from '@/libs/getenv';
import store from '@/store';
import toastr from 'toastr';
import router from '@/libs/router';
import makeQuery from '@/utils/makeQuery';

const api = axios.create({
	baseURL: `${getenv.vars.apiBaseUrl}/api/v1`,
	withCredentials: false,
});

const getApiHeaders = () => {
	let headers = {
		'X-Requested-With': 'XMLHttpRequest',
		Accept: 'application/json',
		'Content-Type': 'application/json',
	};
	let token = store.getters['auth/getToken'];

	if (store.getters['auth/isAuthenticated']) {
		headers.Authorization = `Bearer ${token}`;
	}

	return headers;
};

const axiosGet = async (url, onSuccess, onError) => {
	await api
		.get(url, {
			crossDomain: true,
			headers: getApiHeaders(),
		})
		.then((response) => onSuccess(response))
		.catch((error) => errorHandler(error, onError));
};

const axiosPost = async (url, body, onSuccess, onError) => {
	await api
		.post(url, body, {
			crossDomain: true,
			headers: getApiHeaders(),
		})
		.then((response) => onSuccess(response))
		.catch((error) => {
			errorHandler(error, onError);
		});
};

const axiosPut = async (url, body, onSuccess, onError) => {
	await api
		.put(url, body, {
			crossDomain: true,
			headers: getApiHeaders(),
		})
		.then((response) => onSuccess(response))
		.catch((error) => {
			errorHandler(error, onError);
		});
};

const axiosDelete = async (url, onSuccess, onError) => {
	await api
		.delete(url, {
			crossDomain: true,
			headers: getApiHeaders(),
		})
		.then((response) => onSuccess(response))
		.catch((error) => errorHandler(error, onError));
};

const errorHandler = (error, onError) => {
	let status = error?.response?.status;
	if (status === 500) {
		toastr.error("Whoops! Server Hatoligi ro'y berdi");
	}

	if (status === 401) {
		if (error.config.url !== 'auth/login') {
			store.dispatch('auth/clearAll');
			router.push({ name: 'login' });
			return;
		}
	}

	if (status === 403) {
		toastr.error("Sizda kerakli ruxsatlar yo'q");
	}

	return typeof onError === 'function' && onError(error);
};

const apis = {
	// Авторизация
	auth(body, onSuccess, onError = null) {
		return axiosPost('auth/login', body, onSuccess, onError);
	},
	logout(onSuccess, onError = null) {
		return axiosPost('auth/logout', {}, onSuccess, onError);
	},

	// Заводы
	getFactories(filters, onSuccess, onError = null) {
		return axiosGet(`factory?${makeQuery(filters)}`, onSuccess, onError);
	},
	getFactory(id, onSuccess, onError = null) {
		return axiosGet(`factory/${id}`, onSuccess, onError);
	},
	createFactory(data, onSuccess, onError = null) {
		return axiosPost(`factory`, data, onSuccess, onError);
	},
	updateFactory(id, data, onSuccess, onError = null) {
		return axiosPut(`factory/${id}`, data, onSuccess, onError);
	},
	deleteFactory(id, onSuccess, onError = null) {
		return axiosDelete(`factory/${id}`, onSuccess, onError);
	},

	// Цехи
	getFactoryFloors(filters, onSuccess, onError = null) {
		return axiosGet(
			`factory-floor?${makeQuery(filters)}`,
			onSuccess,
			onError,
		);
	},
	getFactoryFloor(id, onSuccess, onError = null) {
		return axiosGet(`factory-floor/${id}`, onSuccess, onError);
	},
	createFactoryFloor(data, onSuccess, onError = null) {
		return axiosPost(`factory-floor`, data, onSuccess, onError);
	},
	updateFactoryFloor(id, data, onSuccess, onError = null) {
		return axiosPut(`factory-floor/${id}`, data, onSuccess, onError);
	},
	deleteFactoryFloor(id, onSuccess, onError = null) {
		return axiosDelete(`factory-floor/${id}`, onSuccess, onError);
	},

	// Пользователи
	getUsers(filters, onSuccess, onError = null) {
		return axiosGet(`user?${makeQuery(filters)}`, onSuccess, onError);
	},
	getUser(id, onSuccess, onError = null) {
		return axiosGet(`user/${id}`, onSuccess, onError);
	},
	createUser(data, onSuccess, onError = null) {
		return axiosPost(`user`, data, onSuccess, onError);
	},
	updateUser(id, data, onSuccess, onError = null) {
		return axiosPut(`user/${id}`, data, onSuccess, onError);
	},
	deleteUser(id, onSuccess, onError = null) {
		return axiosDelete(`user/${id}`, onSuccess, onError);
	},

	// Приборы
	getDevices(filters, onSuccess, onError = null) {
		return axiosGet(`device?${makeQuery(filters)}`, onSuccess, onError);
	},
	getDevice(id, onSuccess, onError = null) {
		return axiosGet(`device/${id}`, onSuccess, onError);
	},
	createDevice(data, onSuccess, onError = null) {
		return axiosPost(`device`, data, onSuccess, onError);
	},
	updateDevice(id, data, onSuccess, onError = null) {
		return axiosPut(`device/${id}`, data, onSuccess, onError);
	},
	deleteDevice(id, onSuccess, onError = null) {
		return axiosDelete(`device/${id}`, onSuccess, onError);
	},

	getAttributes(filters, onSuccess, onError = null) {
		return axiosGet(`attribute?${makeQuery(filters)}`, onSuccess, onError);
	},

	// Приборы завода
	getFactoryDevices(filters, onSuccess, onError = null) {
		return axiosGet(
			`factory-device?${makeQuery(filters)}`,
			onSuccess,
			onError,
		);
	},
	getFactoryDevice(id, onSuccess, onError = null) {
		return axiosGet(`factory-device/${id}`, onSuccess, onError);
	},
};

export default {
	apis,
	install(app) {
		app.config.globalProperties.$api = apis;
	},
};
