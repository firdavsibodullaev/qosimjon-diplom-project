<template>
    <Layout page-title="Bosh sahifa"></Layout>
</template>

<script>
import Layout from "@/pages/Layout.vue";

export default {
    components: {Layout},
    methods: {},
    beforeMount() {
        document.title = `${this.$env.appName} | Bosh sahifa`;
    }
}
</script>
