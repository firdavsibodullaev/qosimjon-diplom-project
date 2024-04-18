import type { IFactory } from '@/contracts/factory/IFactory';

export interface IFloor {
	id: number;
	name: string;
	number: number;
	is_deleted: boolean;
	factory?: IFactory;
}
