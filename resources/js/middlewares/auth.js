import store from '@/store';

export default (to, from, next) => {
	if (
		to.meta.middleware.includes('auth') &&
		!store.getters['auth/isAuthenticated']
	) {
		store.dispatch('auth/clearAll');
		return 'login';
	}

	return null;
};
