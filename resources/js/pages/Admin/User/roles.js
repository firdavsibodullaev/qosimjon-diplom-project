import { findIndex, findLastIndex } from 'lodash';

export default {
	admin: 'Admin',
	tester: 'Tekshiruvchi',
	director: 'Direktor',
	moderator: 'Moderator',
	worker: 'Ishchi',
	list(currentRole, selectedRole = null) {
		let roles = {
			admin: {
				text: this.admin,
				enabled: ['admin'].includes(currentRole),
			},
			tester: {
				text: this.tester,
				enabled: ['admin'].includes(currentRole),
			},
			director: {
				text: this.director,
				enabled: ['admin'].includes(currentRole),
			},
			moderator: {
				text: this.moderator,
				enabled: ['admin', 'director'].includes(currentRole),
			},
			worker: {
				text: this.worker,
				enabled: ['admin', 'director', 'moderator'].includes(
					currentRole,
				),
			},
		};

		let entries = Object.entries(roles);

		let index =
			selectedRole && !roles[selectedRole]?.enabled
				? findIndex(entries, (e) => e[0] === selectedRole)
				: findLastIndex(entries, (e) => e[1]?.enabled === false) + 1;

		return Object.fromEntries(entries.slice(index));
	},
	isEnabled(currentRole, role) {
		return {
			admin: ['admin'].includes(currentRole),
			tester: ['admin'].includes(currentRole),
			director: ['admin'].includes(currentRole),
			moderator: ['admin', 'director'].includes(currentRole),
			worker: ['admin', 'director', 'moderator'].includes(currentRole),
		}[role];
	},
};
