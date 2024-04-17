import type { IDevice } from '@/contracts/device/IDevice';
import type { ILinks } from '@/contracts/pagination/ILinks';
import type { IMeta } from '@/contracts/pagination/IMeta';

export interface IDevices {
	data: IDevice[];
	links?: ILinks;
	message: string;
	meta?: IMeta;
	success: boolean;
}
