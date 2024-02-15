import store from "@/store";

export default (to, from, next) => {

    if (to.meta.middleware.includes('guest') && store.getters['auth/getUser']) {
        return "index";
    }

    return null;
}
