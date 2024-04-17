import type { IAttribute } from '@/contracts/device/IAttribute';

export interface IValue {
	id: number;
	value: string;
	attribute: IAttribute;
}
