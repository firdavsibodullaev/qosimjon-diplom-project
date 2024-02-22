import Cookies from "js-cookie";

export const sidebarModule = {
    namespaced: true,
    state: () => ({
        collapsed: Cookies.get('sidebar-collapse') === 'true',
        loading: false
    }),
    getters: {
        getCollapsed(state) {
            return state.collapsed;
        },
        getLoading(state) {
            return state.loading;
        }
    },
    mutations: {
        setCollapsed(state) {
            state.collapsed = !state.collapsed;
            Cookies.set('sidebar-collapse', state.collapsed);
        },
        setLoading(state, value) {
            state.loading = value;
        }
    }
}
