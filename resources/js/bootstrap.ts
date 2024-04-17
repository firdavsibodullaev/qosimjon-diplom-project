import { createApp } from 'vue';
import App from '@/App.vue';
import vueRouter from '@/libs/router';
import store from '@/store';
import api from '@/libs/api';
import Antd from 'ant-design-vue';
import getenv from '@/libs/getenv';
import setPrototypes from '@/utils/setPrototypes';

createApp(App)
	.use(setPrototypes)
	.use(vueRouter)
	.use(store)
	.use(api)
	.use(getenv)
	.use(Antd)
	.mount('#app');
