import store from "@/store";

export default (to, from, next) => {

    if (to.meta.middleware.includes('guest') && store.getters['auth/isAuthenticated']) {
        return "index";
    }

    return null;
}
