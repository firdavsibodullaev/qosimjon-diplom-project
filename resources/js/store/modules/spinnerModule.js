export const spinnerModule = {
    namespaced: true,
    state: () => ({
        spinning: false
    }),
    getters: {
        getSpinning(state) {
            return state.spinning;
        }
    },
    mutations: {
        toggleSpinning(state) {
            return state.spinning = !state.spinning;
        }
    }
};
