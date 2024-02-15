<template>
    <button @click="logout">Выйти</button>
</template>

<script>
export default {
    methods: {
        logout() {
            this.$api.logout((response) => {
                this.$store.commit('auth/setToken', null);
                this.$store.commit('auth/setUser', null);
                this.$router.push({name: "login"});
            });
        }
    }
}
</script>
