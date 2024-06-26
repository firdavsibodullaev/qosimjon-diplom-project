declare module 'js-cookie';
declare module 'toastr';
declare global {
	interface Array<T> {
		unique(key?: string | ((array: T[]) => T[])): T[];
	}
}

export {};
