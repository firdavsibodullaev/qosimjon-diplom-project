import store from "@/store";

export default (roles) => {
    let userRole = store.getters['auth/getUser']?.roles[0].key;

    if (!userRole) {
        return true;
    }

    return !roles || roles.includes(userRole);
}
