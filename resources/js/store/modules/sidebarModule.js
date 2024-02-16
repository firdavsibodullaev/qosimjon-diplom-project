import Cookies from "js-cookie";

export const sidebarModule = {
    namespaced: true,
    state: () => ({
        collapsed: Cookies.get('sidebar-collapse') === 'true'
    }),
    getters: {
        getCollapsed(state) {
            return state.collapsed;
        }
    },
    mutations: {
        setCollapsed(state) {
            state.collapsed = !state.collapsed;
            Cookies.set('sidebar-collapse', state.collapsed);
        }
    }
}
