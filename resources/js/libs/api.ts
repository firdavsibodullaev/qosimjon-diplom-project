import axios from 'axios';
import getenv from '@/libs/getenv';
import store from '@/store';
import toastr from 'toastr';
import router from '@/libs/router';
import makeQuery from '@/utils/makeQuery';
import type { IOnError } from '@/contracts/api/IOnError';
import type { IOnSuccess } from '@/contracts/api/IOnSuccess';
import type { IUploadPayload } from '@/contracts/factory-device/IUploadPayload';
import type { IHeader } from '@/contracts/api/IHeader';
import type { IPayload } from '@/contracts/api/IPayload';
import type { IError } from '@/contracts/api/IError';

const api = axios.create({
	baseURL: `${getenv.vars.apiBaseUrl}/api/v1`,
	withCredentials: false,
});

const getApiHeaders = (isFile: boolean = false): IHeader => {
	let headers: IHeader = {
		'X-Requested-With': 'XMLHttpRequest',
		Accept: 'application/json',
		'Content-Type': isFile ? 'multipart/form-data' : 'application/json',
	};
	let token = store.getters['auth/getToken'];

	if (store.getters['auth/isAuthenticated']) {
		headers.Authorization = `Bearer ${token}`;
	}

	return headers;
};

const axiosGet = async (
	url: string,
	onSuccess: IOnSuccess,
	onError?: IOnError,
) => {
	await api
		.get(url, { headers: getApiHeaders() })
		.then((response) => onSuccess(response))
		.catch((error) => errorHandler(error, onError));
};

const axiosPost = async (
	url: string,
	body: IPayload,
	onSuccess: IOnSuccess,
	onError?: IOnError,
) => {
	await api
		.post(url, body, { headers: getApiHeaders() })
		.then((response) => onSuccess(response))
		.catch((error) => errorHandler(error, onError));
};

const axiosPostUpload = async (
	url: string,
	body: IPayload,
	onSuccess: IOnSuccess,
	onError?: IOnError,
) => {
	await api
		.post(url, body, { headers: getApiHeaders(true) })
		.then((response) => onSuccess(response))
		.catch((error) => errorHandler(error, onError));
};

const axiosPut = async (
	url: string,
	body: IPayload,
	onSuccess: IOnSuccess,
	onError?: IOnError,
) => {
	await api
		.put(url, body, { headers: getApiHeaders() })
		.then((response) => onSuccess(response))
		.catch((error) => errorHandler(error, onError));
};

const axiosPutUpload = async (
	url: string,
	body: IPayload,
	onSuccess: IOnSuccess,
	onError?: IOnError,
) => {
	await api
		.post(
			url,
			{ _method: 'put', ...body },
			{ headers: getApiHeaders(true) },
		)
		.then((response) => onSuccess(response))
		.catch((error) => errorHandler(error, onError));
};

const axiosPatch = async (
	url: string,
	body: IPayload,
	onSuccess: IOnSuccess,
	onError?: IOnError,
) => {
	await api
		.patch(url, body, { headers: getApiHeaders() })
		.then((response) => onSuccess(response))
		.catch((error) => errorHandler(error, onError));
};

const axiosPatchUpload = async (
	url: string,
	body: IPayload,
	onSuccess: IOnSuccess,
	onError?: IOnError,
) => {
	await api
		.post(
			url,
			{ _method: 'patch', ...body },
			{ headers: getApiHeaders(true) },
		)
		.then((response) => onSuccess(response))
		.catch((error) => errorHandler(error, onError));
};

const axiosDelete = async (
	url: string,
	onSuccess: IOnSuccess,
	onError?: IOnError,
) => {
	await api
		.delete(url, {
			headers: getApiHeaders(),
		})
		.then((response) => onSuccess(response))
		.catch((error) => errorHandler(error, onError));
};

const errorHandler = (error: IError, onError?: IOnError) => {
	if (!error.response) {
		return;
	}

	let status = error.response.status;
	if (status === 500) {
		toastr.error("Whoops! Server Hatoligi ro'y berdi");
	}

	if (status === 401) {
		if (error.config.url !== 'auth/login') {
			store
				.dispatch('auth/clearAll')
				.then(() => router.push({ name: 'login' }));

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
	auth(data: object, onSuccess: IOnSuccess, onError?: IOnError) {
		return axiosPost('auth/login', data, onSuccess, onError);
	},
	logout(onSuccess: IOnSuccess, onError?: IOnError) {
		return axiosPost('auth/logout', {}, onSuccess, onError);
	},

	// Заводы
	getFactories(
		filters: { [key: string]: string | null | number } | object,
		onSuccess: IOnSuccess,
		onError?: IOnError,
	) {
		return axiosGet(`factory?${makeQuery(filters)}`, onSuccess, onError);
	},
	getFactory(id: number, onSuccess: IOnSuccess, onError?: IOnError) {
		return axiosGet(`factory/${id}`, onSuccess, onError);
	},
	createFactory(data: object, onSuccess: IOnSuccess, onError?: IOnError) {
		return axiosPost(`factory`, data, onSuccess, onError);
	},
	updateFactory(
		id: number,
		data: object,
		onSuccess: IOnSuccess,
		onError?: IOnError,
	) {
		return axiosPut(`factory/${id}`, data, onSuccess, onError);
	},
	deleteFactory(id: number, onSuccess: IOnSuccess, onError?: IOnError) {
		return axiosDelete(`factory/${id}`, onSuccess, onError);
	},

	// Цехи
	getFactoryFloors(
		filters: { [key: string]: string },
		onSuccess: IOnSuccess,
		onError?: IOnError,
	) {
		return axiosGet(
			`factory-floor?${makeQuery(filters)}`,
			onSuccess,
			onError,
		);
	},
	getFactoryFloor(id: number, onSuccess: IOnSuccess, onError?: IOnError) {
		return axiosGet(`factory-floor/${id}`, onSuccess, onError);
	},
	createFactoryFloor(
		data: object,
		onSuccess: IOnSuccess,
		onError?: IOnError,
	) {
		return axiosPost(`factory-floor`, data, onSuccess, onError);
	},
	updateFactoryFloor(
		id: number,
		data: object,
		onSuccess: IOnSuccess,
		onError?: IOnError,
	) {
		return axiosPut(`factory-floor/${id}`, data, onSuccess, onError);
	},
	deleteFactoryFloor(id: number, onSuccess: IOnSuccess, onError?: IOnError) {
		return axiosDelete(`factory-floor/${id}`, onSuccess, onError);
	},

	// Пользователи
	getUsers(
		filters: { [key: string]: string },
		onSuccess: IOnSuccess,
		onError?: IOnError,
	) {
		return axiosGet(`user?${makeQuery(filters)}`, onSuccess, onError);
	},
	getUser(id: number, onSuccess: IOnSuccess, onError?: IOnError) {
		return axiosGet(`user/${id}`, onSuccess, onError);
	},
	createUser(data: object, onSuccess: IOnSuccess, onError?: IOnError) {
		return axiosPost(`user`, data, onSuccess, onError);
	},
	updateUser(
		id: number,
		data: object,
		onSuccess: IOnSuccess,
		onError?: IOnError,
	) {
		return axiosPut(`user/${id}`, data, onSuccess, onError);
	},
	deleteUser(id: number, onSuccess: IOnSuccess, onError?: IOnError) {
		return axiosDelete(`user/${id}`, onSuccess, onError);
	},

	// Приборы
	getDevices(
		filters: { [key: string]: string },
		onSuccess: IOnSuccess,
		onError?: IOnError,
	) {
		return axiosGet(`device?${makeQuery(filters)}`, onSuccess, onError);
	},
	getDevice(id: number, onSuccess: IOnSuccess, onError?: IOnError) {
		return axiosGet(`device/${id}`, onSuccess, onError);
	},
	createDevice(data: object, onSuccess: IOnSuccess, onError?: IOnError) {
		return axiosPost(`device`, data, onSuccess, onError);
	},
	updateDevice(
		id: number,
		data: object,
		onSuccess: IOnSuccess,
		onError?: IOnError,
	) {
		return axiosPut(`device/${id}`, data, onSuccess, onError);
	},
	deleteDevice(id: number, onSuccess: IOnSuccess, onError?: IOnError) {
		return axiosDelete(`device/${id}`, onSuccess, onError);
	},

	getAttributes(
		filters: { [key: string]: string },
		onSuccess: IOnSuccess,
		onError?: IOnError,
	) {
		return axiosGet(`attribute?${makeQuery(filters)}`, onSuccess, onError);
	},

	// Приборы завода
	getFactoryDevices(
		filters: { [key: string]: number | string | null },
		onSuccess: IOnSuccess,
		onError?: IOnError,
	) {
		return axiosGet(
			`factory-device?${makeQuery(filters)}`,
			onSuccess,
			onError,
		);
	},
	createFactoryDevice(
		data: IUploadPayload,
		onSuccess: IOnSuccess,
		onError?: IOnError,
	) {
		return axiosPost(`factory-device`, data, onSuccess, onError);
	},
	updateFactoryDevice(
		id: number,
		data: IUploadPayload,
		onSuccess: IOnSuccess,
		onError?: IOnError,
	) {
		return axiosPut(`factory-device/${id}`, data, onSuccess, onError);
	},

	getCalibration(
		filters: { [key: string]: number | string | null },
		onSuccess: IOnSuccess,
		onError?: IOnError,
	) {
		return axiosGet(
			`calibration?${makeQuery(filters)}`,
			onSuccess,
			onError,
		);
	},
	createCalibration(
		payload: IUploadPayload,
		onSuccess: IOnSuccess,
		onError?: IOnError,
	) {
		return axiosPostUpload('calibration', payload, onSuccess, onError);
	},
	updateCalibration(
		id: number,
		payload: IUploadPayload,
		onSuccess: IOnSuccess,
		onError?: IOnError,
	) {
		return axiosPutUpload(`calibration/${id}`, payload, onSuccess, onError);
	},

	getApplication(
		filters: { [key: string]: number | string | null },
		onSuccess: IOnSuccess,
		onError?: IOnError,
	) {
		return axiosGet(
			`application?${makeQuery(filters)}`,
			onSuccess,
			onError,
		);
	},
	acceptApplication(id: number, onSuccess: IOnSuccess, onError?: IOnError) {
		return axiosPatch(`application/accept/${id}`, {}, onSuccess, onError);
	},
	approveApplication(
		id: number,
		body: object,
		onSuccess: IOnSuccess,
		onError?: IOnError,
	) {
		return axiosPatchUpload(
			`application/approve/${id}`,
			body,
			onSuccess,
			onError,
		);
	},
	rejectApplication(
		id: number,
		body: object,
		onSuccess: IOnSuccess,
		onError?: IOnError,
	) {
		return axiosPatchUpload(
			`application/reject/${id}`,
			body,
			onSuccess,
			onError,
		);
	},
};

export default {
	apis,
	install(app: any) {
		app.config.globalProperties.$api = apis;
	},
};
