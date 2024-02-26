export default {
    admin: 'Admin',
    tester: 'Tekshiruvchi',
    director: 'Direktor',
    moderator: 'Moderator',
    worker: 'Ishchi',
    forRole(role) {

        if (role === 'admin') {
            return {
                admin: this.admin,
                tester: this.tester,
                director: this.director,
                moderator: this.moderator,
                worker: this.worker,
            };
        }

        if (role === 'director') {
            return {
                moderator: this.moderator,
                worker: this.worker,
            };
        }

        if (role === 'moderator') {
            return {
                worker: this.worker,
            };
        }

        return {};
    }
};
