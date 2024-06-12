import Index from '@/pages/Index.vue';
import Login from '@/pages/Auth/Login.vue';
import FactoryIndex from '@/pages/Admin/Factory/FactoryIndex.vue';
import FactoryFloorIndex from '@/pages/Admin/FactoryFloor/FactoryFloorIndex.vue';
import UserIndex from '@/pages/Admin/User/UserIndex.vue';
import DeviceIndex from '@/pages/Admin/Device/DeviceIndex.vue';
import Page403 from '@/pages/Error/Page403.vue';
import Page404 from '@/pages/Error/Page404.vue';
import FactoryDeviceIndex from '@/pages/Moderator/FactoryDeviceIndex.vue';
import Calibration from '@/pages/Worker/Calibration.vue';
import Application from '@/pages/Tester/Application.vue';

export default [
	{
		path: '/',
		name: 'index',
		component: Index,
		meta: {
			middleware: ['auth'],
			sidebar: true,
			main: true,
			text: 'Bosh sahifa',
		},
	},
	{
		path: '/login',
		name: 'login',
		component: Login,
		meta: {
			middleware: ['guest'],
			sidebar: false,
		},
	},
	{
		name: 'settings',
		path: '/settings',
		meta: {
			middleware: ['auth'],
			sidebar: true,
			role: ['admin', 'director'],
			type: 'giving_for_test',
			main: true,
			text: 'Sozlamalar',
		},
		children: [
			{
				name: 'factory',
				path: 'factory',
				component: FactoryIndex,
				meta: {
					public: true,
					middleware: ['auth'],
					sidebar: true,
					role: ['admin'],
					type: 'giving_for_test',
					text: 'Zavodlar',
				},
			},
			{
				name: 'floor',
				path: 'factory-floor',
				component: FactoryFloorIndex,
				meta: {
					public: true,
					middleware: ['auth'],
					sidebar: true,
					role: ['admin', 'director'],
					type: 'giving_for_test',
					text: 'Sexlar',
				},
			},
			{
				name: 'user',
				path: 'user',
				component: UserIndex,
				meta: {
					public: true,
					middleware: ['auth'],
					sidebar: true,
					role: ['admin', 'director'],
					type: 'giving_for_test',
					text: 'Foydalanuvchilar',
				},
			},
			{
				name: 'device',
				path: 'device',
				component: DeviceIndex,
				meta: {
					public: true,
					middleware: ['auth'],
					sidebar: true,
					role: ['admin'],
					type: 'giving_for_test',
					text: 'Priborlar',
				},
			},
		],
	},
	{
		path: '/factory-devices',
		name: 'factoryDevices',
		component: FactoryDeviceIndex,
		meta: {
			middleware: ['auth'],
			sidebar: true,
			role: ['moderator'],
			type: 'giving_for_test',
			main: true,
			text: "Pribor qo'shish",
		},
	},
	{
		path: '/calibration',
		name: 'calibration',
		component: Calibration,
		meta: {
			middleware: ['auth'],
			sidebar: true,
			role: ['worker'],
			type: 'giving_for_test',
			main: true,
			text: 'Ariza berish',
		},
	},
	{
		path: '/application',
		name: 'application',
		component: Application,
		meta: {
			middleware: ['auth'],
			sidebar: true,
			role: ['tester'],
			type: 'tester',
			main: true,
			text: 'Arizalar',
		},
	},
	{
		name: '403',
		path: '/403',
		component: Page403,
		meta: {
			sidebar: false,
		},
	},
	{
		path: '/:pathMatch(.*)*',
		component: Page404,
		meta: {
			sidebar: false,
		},
	},
];
