<template>
    <a-drawer
        :title="$store.getters['drawer/getDrawerTitle']"
        :width="720"
        :open="$store.getters['drawer/getOpen']"
        :body-style="{ paddingBottom: '80px' }"
        :footer-style="{ textAlign: 'right' }"
        @close="onClose"
    >
        <component :is="component" :key="componentKey" v-bind="$store.getters['drawer/getComponentProps']"/>
    </a-drawer>
</template>
<script>

export default {
    name: 'Drawer',
    props: {
        component: {
            required: true
        },
        componentKey: {
            type: String,
        }
    },
    methods: {
        onClose() {
            this.$store.dispatch('drawer/clearDrawer');
        }
    }
}
</script>
