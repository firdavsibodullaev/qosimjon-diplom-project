import type { IDevice } from '@/contracts/device/IDevice';
import type { ILinks } from '@/contracts/pagination/ILinks';
import type { IMeta } from '@/contracts/pagination/IMeta';
import type { IOnSuccessData } from '@/contracts/api/IOnSuccess';

export interface IDevices extends IOnSuccessData {
	data: IDevice[];
	message: string;
	success: boolean;
	links?: ILinks;
	meta?: IMeta;
}
