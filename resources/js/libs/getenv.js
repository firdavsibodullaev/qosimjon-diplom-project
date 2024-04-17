const envVars = {
	appName: import.meta.env.VITE_APP_NAME,
	apiBaseUrl: import.meta.env.VITE_APP_URL,
};
export default {
	vars: envVars,
	install(app) {
		app.config.globalProperties.$env = this.vars;
	},
};
