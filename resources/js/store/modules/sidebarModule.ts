import Cookies from 'js-cookie';

export const sidebarModule = {
	namespaced: true,
	state: () => ({
		collapsed: Cookies.get('sidebar-collapse') === 'true',
		loading: false,
	}),
	getters: {
		getCollapsed(state: any) {
			return state.collapsed;
		},
		getLoading(state: any) {
			return state.loading;
		},
	},
	mutations: {
		setCollapsed(state: any) {
			state.collapsed = !state.collapsed;
			Cookies.set('sidebar-collapse', state.collapsed);
		},
		setLoading(state: any, value: any) {
			state.loading = value;
		},
	},
};
