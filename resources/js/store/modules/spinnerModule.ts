export const spinnerModule = {
	namespaced: true,
	state: () => ({
		spinner: {
			main: {
				spinning: false,
			},
			drawer: {
				spinning: false,
			},
		},
	}),
	getters: {
		getMainSpinning(state: any) {
			return state.spinner.main.spinning;
		},
		getDrawerSpinning(state: any) {
			return state.spinner.drawer.spinning;
		},
	},
	mutations: {
		toggleSpinning(state: any, spinner: string = 'main') {
			return (state.spinner[spinner].spinning =
				!state.spinner[spinner].spinning);
		},
	},
};
