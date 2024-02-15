import store from "@/store";

export default (to, from, next) => {

    if (to.meta.middleware.includes('auth') && !store.getters['auth/getUser']) {
        return "login";
    }

    return null;
}
