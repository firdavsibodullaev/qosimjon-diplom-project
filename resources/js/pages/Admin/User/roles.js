export default {

    admin: 'Admin',
    tester: 'Tekshiruvchi',
    director: 'Direktor',
    moderator: 'Moderator',
    worker: 'Ishchi',
    list(role) {
        return {
            admin: {
                text: this.admin,
                enabled: ['admin'].includes(role)
            },
            tester: {
                text: this.tester,
                enabled: ['admin'].includes(role)
            },
            director: {
                text: this.director,
                enabled: ['admin'].includes(role)
            },
            moderator: {
                text: this.moderator,
                enabled: ['admin', 'director'].includes(role)
            },
            worker: {
                text: this.worker,
                enabled: ['admin', 'director', 'moderator'].includes(role)
            }
        };
    },
    isEnabled(currentRole, role) {
        return {
            admin: ['admin'].includes(currentRole),
            tester: ['admin'].includes(currentRole),
            director: ['admin'].includes(currentRole),
            moderator: ['admin', 'director'].includes(currentRole),
            worker: ['admin', 'director', 'moderator'].includes(currentRole)

        }[role];
    }
};
