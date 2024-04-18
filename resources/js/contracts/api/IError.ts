import type { IErrorResponse } from '@/contracts/factory-device/IErrorResponse';

export interface IError {
	code: string;
	config: {
		url: string;
	};
	response: IErrorResponse;
	message: string;
	name: string;
	request: {
		[key: string]: any;
	};
}
