<template>
    <a-drawer
        :title="$store.getters['drawer/getDrawerTitle']"
        :width="width"
        :open="$store.getters['drawer/getOpen']"
        :body-style="{ paddingBottom: '80px' }"
        :footer-style="{ textAlign: 'right' }"
        @close="onClose"
    >

        <Spinner type="drawer">
            <component :is="component"
                       ref="myComponent"
                       :key="componentKey"
                       v-bind="$store.getters['drawer/getComponentProps']"/>
        </Spinner>
    </a-drawer>
</template>
<script>

import Spinner from "@/components/Spinner.vue";

export default {
    name: 'Drawer',
    components: {Spinner},
    props: {
        component: {
            required: true
        },
        componentKey: {
            type: String,
        },
        width: {
            type: Number,
            default: 720
        }
    },
    computed: {
        isOpen() {
            return this.$store.getters['drawer/getOpen'];
        }
    },
    methods: {
        onClose() {
            this.$store.dispatch('drawer/clearDrawer');
        }
    },
    watch: {
        isOpen(newValue) {
            if (newValue) {
                if (!this.$store.getters[`spinner/getDrawerSpinning`]) {
                    this.$store.commit('spinner/toggleSpinning', 'drawer');
                }
                setTimeout(() => {
                    let componentRef = this.$refs.myComponent;
                    if (typeof componentRef?.init === 'function') {
                        componentRef.init();
                    }
                }, 1);
            } else {
                if (this.$store.getters[`spinner/getDrawerSpinning`]) {
                    this.$store.commit('spinner/toggleSpinning', 'drawer');
                }
            }

        }
    }
}
</script>
