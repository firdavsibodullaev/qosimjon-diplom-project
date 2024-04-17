import { createStore } from 'vuex';
import { authModule } from '@/store/modules/authModule';
import { sidebarModule } from '@/store/modules/sidebarModule';
import { spinnerModule } from '@/store/modules/spinnerModule';
import { drawerModule } from '@/store/modules/drawerModule';
import { factoryModule } from '@/store/modules/factoryModule';

export default createStore({
	modules: {
		auth: authModule,
		sidebar: sidebarModule,
		spinner: spinnerModule,
		drawer: drawerModule,
		factory: factoryModule,
	},
});
