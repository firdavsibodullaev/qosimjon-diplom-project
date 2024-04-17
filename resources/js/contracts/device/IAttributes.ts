import type { IValue } from '@/contracts/device/IValue';

export interface IAttributes {
	id: number;
	value: IValue;
	measurement_unit: string;
}
