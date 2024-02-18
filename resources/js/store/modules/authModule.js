import Cookies from "js-cookie";
import api from "@/libs/api";

export const authModule = {
    namespaced: true,
    state: () => ({
        user: localStorage.getItem("user"),
        token: Cookies.get("token"),
        expiresAt: null
    }),
    getters: {
        getUser(state) {
            let user = state.user;

            return user ? JSON.parse(user) : user;
        },
        getToken(state, getters) {
            return state.token;
        },
        getExpiresAt(state) {
            return state.expiresAt;
        },
        isAuthenticated(state) {
            return !!state.user && !!state.token;
        }
    },
    mutations: {
        setUser(state, user) {
            state.user = user ? JSON.stringify(user) : user;
            user ? localStorage.setItem("user", state.user) : localStorage.removeItem("user");
        },
        setToken(state, token) {
            token ? Cookies.set("token", token) : Cookies.remove("token");

            state.token = token;
        },
        setExpiresAt(state, expiresAt) {
            state.expiresAt = expiresAt;
        }
    },
    actions: {
        clearAll(context) {
            if (context.getters['getToken']) {
                context.commit('setUser', {user: 'user'});
                api.apis.logout({});
            }

            context.commit('setUser', null);
            context.commit('setToken', null);
            context.commit('setExpiresAt', null);
        }
    }
}
