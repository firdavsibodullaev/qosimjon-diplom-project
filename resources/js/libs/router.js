import {createRouter, createWebHistory} from "vue-router";
import Index from "@/pages/Index.vue";
import Login from "@/pages/Auth/Login.vue";
import kernel from "@/middlewares/kernel";

const routes = [
    {
        path: '/',
        name: 'index',
        component: Index,
        meta: {
            middleware: ['auth']
        },
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            middleware: ['guest']
        },
    }
];


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

    next();
});

export default vueRouter;
