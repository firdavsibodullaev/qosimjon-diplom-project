import type { Ref } from 'vue';

export interface IUseDevices {
	devices: Ref;
	total: Ref;
	page: Ref;
	perPage: Ref;
	getDevices: (filters: any) => Promise<void>;
}
