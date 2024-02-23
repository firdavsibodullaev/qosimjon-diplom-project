import store from "@/store";

export default (roles) => {
    let userRole = store.getters['auth/getUser'].roles[0].key;

    return userRole === 'admin' || !roles || roles.includes(userRole);
}
