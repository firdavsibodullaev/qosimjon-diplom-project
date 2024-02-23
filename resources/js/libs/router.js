import {createRouter, createWebHistory} from "vue-router";
import kernel from "@/middlewares/kernel";
import hasPermission from "@/utils/hasPermission";
import routes from "@/libs/routes";


const vueRouter = createRouter({
    routes: routes,
    history: createWebHistory()
});

vueRouter.beforeEach((to, from, next) => {

    let middlewares = to.meta.middleware ?? [];
    let nextPath = null;
    for (let i = 0; i < middlewares.length; i++) {

        nextPath = kernel(middlewares[i], {to, from, next});

        if (nextPath) {
            return next({name: nextPath});
        }
    }

    if (!hasPermission(to.meta?.role)) {
        return next({name: '403'})
    }

    next();
});

export default vueRouter;
