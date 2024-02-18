<template>
    <Layout>
        <div></div>
    </Layout>
</template>
<script>

import Layout from "@/pages/Layout.vue";

export default {
    components: {Layout},
    beforeMount() {
        document.title = `${this.$env.appName} | Zavodlar`;
    }
}
</script>
