export const factoryModule = {
    namespaced: true,
    state: () => ({
        isReload: false
    }),
    getters: {
        getIsReload(state) {
            return state.isReload;
        },
    },
    mutations: {
        setIsReload(state, data) {
            state.isReload = !!data;
        }
    },
    actions: {}
}
