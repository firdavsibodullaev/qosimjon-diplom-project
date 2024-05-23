import Cookies from 'js-cookie';

export const drawerModule = {
	namespaced: true,
	state: () => ({
		open: false,
		componentProps: Cookies.get('drawer-component-props'),
		drawerTitle: null,
	}),
	getters: {
		getOpen(state: any) {
			return state.open;
		},
		getComponentProps(state: any) {
			let data = state.componentProps;

			return data ? JSON.parse(data) : data;
		},
		getDrawerTitle(state: any) {
			return state.drawerTitle;
		},
	},
	mutations: {
		setOpen(state: any, value: boolean) {
			state.open = value;
		},
		setComponentProps(state: any, data: any) {
			state.componentProps = data ? JSON.stringify(data) : null;
			Cookies.set('drawer-component-props', state.componentProps);
		},
		setDrawerTitle(state: any, data: any) {
			state.drawerTitle = data;
		},
	},
	actions: {
		clearDrawer(context: any) {
			context.commit('setComponentProps', null);
			context.commit('setOpen', false);
			context.commit('setDrawerTitle', null);
		},
	},
};
