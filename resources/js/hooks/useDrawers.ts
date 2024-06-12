import type { Component } from 'vue';
import { ref } from 'vue';
import Types from '@/enums/drawer/Types';
import store from '@/store';
import type { IUseDrawers } from '@/contracts/drawer/IUseDrawers';

export const useDrawers = (
	createComponent?: Component,
	editComponent?: Component,
	showComponent?: Component,
): IUseDrawers => {
	const componentByType: Record<Types, Component | undefined> = {
		[Types.Create]: createComponent,
		[Types.Edit]: editComponent,
		[Types.Show]: showComponent,
	};
	const component = ref<Component | null | undefined>(null);
	const componentKey = ref('');

	const getComponent = (type: Types) => {
		return componentByType[type];
	};

	const openDrawer = (
		type: Types,
		key: string,
		title: string,
		props?: object,
	): void => {
		component.value = getComponent(type);

		componentKey.value = key;

		if (props) {
			store.commit('drawer/setComponentProps', props);
		}
		store.commit('drawer/setDrawerTitle', title);

		store.commit('drawer/setOpen', true);
	};

	return { openDrawer, componentKey, component };
};
