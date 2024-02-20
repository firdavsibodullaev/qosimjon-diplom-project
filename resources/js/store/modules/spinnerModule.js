export const spinnerModule = {
    namespaced: true,
    state: () => ({
        spinner: {
            main: {
                spinning: false,
            },
            drawer: {
                spinning: false
            },
        }
    }),
    getters: {
        getMainSpinning(state) {
            return state.spinner.main.spinning;
        },
        getDrawerSpinning(state) {
            return state.spinner.drawer.spinning;
        }
    },
    mutations: {
        toggleSpinning(state, spinner = 'main') {
            return state.spinner[spinner].spinning = !state.spinner[spinner].spinning;
        }
    }
};
