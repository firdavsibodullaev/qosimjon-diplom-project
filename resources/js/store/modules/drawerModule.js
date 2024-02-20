import Cookies from "js-cookie";

export const drawerModule = {
    namespaced: true,
    state: () => ({
        open: false,
        componentProps: Cookies.get('drawer-component-props'),
        drawerTitle: null,
    }),
    getters: {
        getOpen(state) {
            return state.open;
        },
        getComponentProps(state) {
            let data = state.componentProps;

            return data ? JSON.parse(data) : data;
        },
        getDrawerTitle(state) {
            return state.drawerTitle
        }
    },
    mutations: {
        setOpen(state, value) {
            state.open = value;
        },
        setComponentProps(state, data) {
            state.componentProps = data ? JSON.stringify(data) : null;
            Cookies.set('drawer-component-props', state.componentProps);
        },
        setDrawerTitle(state, data) {
            state.drawerTitle = data;
        }
    },
    actions: {
        clearDrawer(context) {
            context.commit('setComponentProps', null);
            context.commit('setOpen', false);
            context.commit('setDrawerTitle', null);
        }
    }
}
