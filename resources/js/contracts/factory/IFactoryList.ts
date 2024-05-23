import type { IOnSuccessData } from '@/contracts/api/IOnSuccess';
import type { ILinks } from '@/contracts/pagination/ILinks';
import type { IMeta } from '@/contracts/pagination/IMeta';
import type { IFactory } from '@/contracts/factory/IFactory';

export interface IFactoryList extends IOnSuccessData {
	data: IFactory[];
	message: string;
	success: boolean;
	links?: ILinks;
	meta?: IMeta;
}
