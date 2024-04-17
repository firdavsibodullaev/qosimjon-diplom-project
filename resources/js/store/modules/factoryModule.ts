import api from '@/libs/api';

export const factoryModule = {
	namespaced: true,
	state: () => ({
		isReload: false,
		list: localStorage.getItem('factories-list'),
	}),
	getters: {
		getIsReload(state: any) {
			return state.isReload;
		},
		getList(state: any) {
			let list = state.list;

			if (!list) return null;

			let timestamp = list.expiresAt;

			if (new Date().getTime() >= timestamp) {
				localStorage.removeItem('factories-list');
				return null;
			}

			return JSON.parse(list).data;
		},
	},
	mutations: {
		setIsReload(state: any, data: any) {
			state.isReload = !!data;
		},
		setList(state: any, data = null) {
			if (data) {
				let expiresAt = new Date().getTime() + 60 * 60 * 1000;
				state.list = JSON.stringify({ expiresAt, data });
				localStorage.setItem('factories-list', state.list);
			} else {
				localStorage.removeItem('factories-list');
			}
		},
	},
	actions: {
		async getOrFetchList(state: any) {
			if (!state.getters['getList']) {
				await api.apis.getFactories(
					{ list: 1 },
					({ data }: { data: any }) =>
						state.commit('setList', data.data),
				);
			}
		},
	},
};
