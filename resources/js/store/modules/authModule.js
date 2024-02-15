import Cookies from "js-cookie";

export const authModule = {
    namespaced: true,
    state: () => ({
        user: localStorage.getItem("user"),
        token: Cookies.get("token"),
        expiresAt: null
    }),
    getters: {
        getUser(state) {
            return state.user;
        },
        getToken(state, getters) {
            return state.token;
        },
        getExpiresAt(state) {
            return state.expiresAt;
        }
    },
    mutations: {
        setUser(state, user) {
            user ? localStorage.setItem("user", user) : localStorage.removeItem("user");
            state.user = user;
        },
        setToken(state, token) {
            token ? Cookies.set("token", token) : Cookies.remove("token");

            state.token = token;
        },
        setExpiresAt(state, expiresAt) {
            state.expiresAt = expiresAt;
        }
    },
    actions: {}
}
