export interface IErrorResponse {
	data: {
		message: string;
		errors?: {
			[key: string]: string[];
		};
	};
	status: number;
}
