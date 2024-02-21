import api from "@/libs/api";

export const factoryModule = {
    namespaced: true,
    state: () => ({
        isReload: false,
        list: localStorage.getItem("factories-list"),
    }),
    getters: {
        getIsReload(state) {
            return state.isReload;
        },
        getList(state) {
            let list = state.list;

            if (!list) return null;

            let timestamp = list.expiresAt;

            if ((new Date()).getTime() >= timestamp) {
                localStorage.removeItem('factories-list')
                return null;
            }

            return JSON.parse(list);
        }
    },
    mutations: {
        setIsReload(state, data) {
            state.isReload = !!data;
        },
        setList(state, data = null) {
            if (data) {
                data.expiresAt = (new Date).getTime() + 60 * 60 * 1000;
                state.list = JSON.stringify(data);
                localStorage.setItem("factories-list", state.list);
            } else {
                localStorage.removeItem("factories-list");
            }
        }
    },
    actions: {
        async getOrFetchList(state) {
            if (!state.getters['getList']) {
                await api.apis.getFactories({list: 1}, ({data}) => state.commit('setList', data.data));
            }
        }
    }
}
