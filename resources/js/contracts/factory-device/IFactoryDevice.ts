import type { position } from '@/enums/factory-device/position';
import type { status } from '@/enums/factory-device/status';
import type { IDevice } from '@/contracts/device/IDevice';
import type { IFactory } from '@/contracts/factory/IFactory';
import type { IFloor } from '@/contracts/floor/IFloor';

export interface IFactoryDevice {
	id: number;
	number: string;
	full_number: string;
	position: position;
	status: status;
	device: IDevice;
	factory: IFactory;
	factory_floor: IFloor;
}
