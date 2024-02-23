<template>
    <Layout page-title="Priborni qo'shish">
        {{ store.getters['auth/getUser'].floor?.number}}{{ store.getters['auth/getUser'].floor?.factory.number}}12313
    </Layout>
</template>
<script setup>
import Layout from "@/pages/Layout.vue";
import store from "../../store";
</script>
