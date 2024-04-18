import type { RawAxiosRequestHeaders } from 'axios';

export interface IHeader extends RawAxiosRequestHeaders {
	'X-Requested-With': string;
	Accept: string;
	'Content-Type': string;
	Authorization?: string;
}
