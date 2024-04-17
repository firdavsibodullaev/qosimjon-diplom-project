import Cookies from 'js-cookie';
import api from '@/libs/api';
import type { IUser } from '@/contracts/user/IUser';

export const authModule = {
	namespaced: true,
	state: () => ({
		user: localStorage.getItem('user'),
		token: Cookies.get('token'),
		expiresAt: null,
	}),
	getters: {
		getUser(state: any): IUser {
			let user = state.user;

			return user ? JSON.parse(user) : user;
		},
		getToken(state: any, getters: any) {
			return state.token;
		},
		getExpiresAt(state: any) {
			return state.expiresAt;
		},
		isAuthenticated(state: any) {
			return !!state.user && !!state.token;
		},
	},
	mutations: {
		setUser(state: any, user: any) {
			state.user = user ? JSON.stringify(user) : user;
			user
				? localStorage.setItem('user', state.user)
				: localStorage.removeItem('user');
		},
		setToken(state: any, token: any) {
			token ? Cookies.set('token', token) : Cookies.remove('token');

			state.token = token;
		},
		setExpiresAt(state: any, expiresAt: any) {
			state.expiresAt = expiresAt;
		},
	},
	actions: {
		clearAll(context: any) {
			if (context.getters['getToken']) {
				context.commit('setUser', { user: 'user' });
				api.apis.logout({});
			}

			context.commit('setUser', null);
			context.commit('setToken', null);
			context.commit('setExpiresAt', null);
		},
	},
};
