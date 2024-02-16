import {createStore} from "vuex";
import {authModule} from "@/store/modules/authModule";
import {sidebarModule} from "@/store/modules/sidebarModule";

export default createStore({
    modules: {
        auth: authModule,
        sidebar: sidebarModule
    }
});
