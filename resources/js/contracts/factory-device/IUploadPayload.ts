import type { position } from '@/enums/factory-device/position';
import type { status } from '@/enums/factory-device/status';

export interface IUploadPayload {
	device_id: number | null;
	factory_floor_id: number | null;
	factory_id: number | null;
	number: number | null;
	position: position | null;
	status: status | null;
}
