import type { IOnSuccessData } from '@/contracts/api/IOnSuccess';
import type { ILinks } from '@/contracts/pagination/ILinks';
import type { IMeta } from '@/contracts/pagination/IMeta';
import type { IFactoryDevice } from '@/contracts/factory-device/IFactoryDevice';

export interface IFactoryDevices extends IOnSuccessData {
	data: IFactoryDevice[];
	message: string;
	success: boolean;
	links: ILinks;
	meta: IMeta;
}
