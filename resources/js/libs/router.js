import {createRouter, createWebHistory} from "vue-router";
import Index from "@/pages/Index.vue";
import Login from "@/pages/Auth/Login.vue";
import kernel from "@/middlewares/kernel";
import store from "@/store";
import Page403 from "@/pages/Error/Page403.vue";
import Page404 from "@/pages/Error/Page404.vue";
import FactoryIndex from "@/pages/Admin/Factory/FactoryIndex.vue";
import FactoryFloorIndex from "@/pages/Admin/FactoryFloor/FactoryFloorIndex.vue";
import UserIndex from "@/pages/Admin/User/UserIndex.vue";

const routes = [
    {
        path: '/',
        name: 'index',
        component: Index,
        meta: {
            middleware: ['auth'],
            sidebar: true,
            main: true,
            text: 'Bosh sahifa'
        },
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            middleware: ['guest'],
            sidebar: false
        }
    },
    {
        name: 'settings',
        path: '/settings',
        meta: {
            middleware: ['auth'],
            sidebar: true,
            role: ['admin'],
            main: true,
            text: 'Sozlamalar'
        },
        children: [
            {
                name: 'factory',
                path: 'factory',
                component: FactoryIndex,
                meta: {
                    middleware: ['auth'],
                    sidebar: true,
                    role: ['admin'],
                    text: 'Zavodlar'
                }
            },
            {
                name: 'floor',
                path: 'factory-floor',
                component: FactoryFloorIndex,
                meta: {
                    middleware: ['auth'],
                    sidebar: true,
                    role: ['admin'],
                    text: 'Sexlar'
                }
            },
            {
                name: 'user',
                path: 'user',
                component: UserIndex,
                meta: {
                    middleware: ['auth'],
                    sidebar: true,
                    role: ['admin'],
                    text: 'Foydalanuvchilar'
                }
            }
        ]
    },
    {
        name: '403',
        path: '/403',
        component: Page403,
        meta: {
            sidebar: false
        }
    },
    {
        path: '/:pathMatch(.*)*',
        component: Page404,
        meta: {
            sidebar: false
        }
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

    let user = store.getters['auth/getUser'];

    if (to.meta.role && !user.roles.filter(role => to.meta.role.includes(role.key)).length) {
        return next({name: '403'})
    }

    next();
});

export default vueRouter;
