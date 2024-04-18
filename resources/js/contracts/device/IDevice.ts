import type { IAttributes } from '@/contracts/device/IAttributes';

export interface IDevice {
	id: number;
	name: string;
	attributes?: IAttributes[];
}
