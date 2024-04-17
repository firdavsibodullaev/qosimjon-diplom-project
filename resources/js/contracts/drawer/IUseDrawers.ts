import Types from '@/enums/drawer/Types';
import type { Ref } from 'vue';

export interface IUseDrawers {
	openDrawer: (
		type: Types,
		key: string,
		title: string,
		props?: object,
	) => void;
	componentKey: Ref;
	component: Ref;
}
