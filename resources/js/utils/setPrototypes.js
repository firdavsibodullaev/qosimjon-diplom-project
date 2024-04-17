import arrayUnique from '@/utils/prototypes/arrayUnique';

export default {
	install(app) {
		if (!Array.prototype.unique) {
			arrayUnique();
		}
	},
};
