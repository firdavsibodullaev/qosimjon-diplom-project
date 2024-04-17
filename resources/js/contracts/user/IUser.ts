import type { IFloor } from '@/contracts/floor/IFloor';
import type { IRole } from '@/contracts/user/IRole';

export interface IUser {
	id: number;
	first_name: string;
	last_name: string;
	name: string;
	username: string;
	floors?: IFloor[];
	roles?: IRole[];
	has_permission_to_all_floors?: boolean;
}
