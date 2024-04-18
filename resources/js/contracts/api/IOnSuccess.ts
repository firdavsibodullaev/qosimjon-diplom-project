import type {
	AxiosResponse,
	AxiosResponseHeaders,
	InternalAxiosRequestConfig,
	RawAxiosResponseHeaders,
} from 'axios';

export type IOnSuccess = (response: IOnSuccessWrapper) => void;

export interface IOnSuccessWrapper extends AxiosResponse {
	data: IOnSuccessData;
	status: number;
	statusText: string;
	headers: RawAxiosResponseHeaders | AxiosResponseHeaders;
	config: InternalAxiosRequestConfig<any>;
	request?: any;
}

export interface IOnSuccessData {
	message: string;
	success: boolean;
}
