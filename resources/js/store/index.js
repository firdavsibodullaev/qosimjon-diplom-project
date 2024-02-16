import {createStore} from "vuex";
import {authModule} from "@/store/modules/authModule";
import {sidebarModule} from "@/store/modules/sidebarModule";
import {spinnerModule} from "@/store/modules/spinnerModule";

export default createStore({
    modules: {
        auth: authModule,
        sidebar: sidebarModule,
        spinner: spinnerModule
    }
});
