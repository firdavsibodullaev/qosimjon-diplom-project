/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import {createApp} from "vue";
import Index from "@/App.vue";
import vueRouter from "@/libs/router";
import store from "@/store";
import api from "@/libs/api";

const router = vueRouter;

createApp(Index)
    .use(router)
    .use(store)
    .use(api)
    .mount('#app');

