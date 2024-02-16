/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import {createApp} from "vue";
import App from "@/App.vue";
import vueRouter from "@/libs/router";
import store from "@/store";
import api from "@/libs/api";
import Antd from "ant-design-vue";
import * as Icons from "@ant-design/icons-vue";
import getenv from "@/libs/getenv";


const vueApp = createApp(App);


Object.keys(Icons).forEach(component => {
    vueApp.component(component, Icons[component]);
});

const router = vueRouter;


vueApp.use(router)
    .use(store)
    .use(api)
    .use(getenv)
    .use(Antd)
    .mount('#app');

