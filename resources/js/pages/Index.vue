<template>
    <Layout>
        Hello
    </Layout>
</template>

<script>
import Layout from "@/pages/Layout.vue";

export default {
    components: {Layout},
    methods: {},
    beforeMount() {
        document.title = `${this.$env.appName} | Главная страница`;
    }
}
</script>
