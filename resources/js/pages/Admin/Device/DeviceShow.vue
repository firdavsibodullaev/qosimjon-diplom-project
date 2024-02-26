<template>
    <div class="mt-4" v-if="data">
        <div class="grid grid-cols-2 gap-6 mb-4">
            <div class="text-lg"><strong>Nomi:</strong></div>
            <div class="text-lg">{{ data.name }}</div>
        </div>
        <div class="grid grid-cols-2 gap-6 mb-4">
            <div class="text-lg"><strong>Hususiyatlari:</strong></div>
            <div class="text-lg">
                <div class="my-2"
                    v-for="(attribute, index) in data.attributes"
                     :key="`attribute-${index}`">
                    <strong>{{ attribute.value.attribute.name }}:</strong> {{ attribute.value.value }} ({{ attribute.measurement_unit }})
                </div>
            </div>
        </div>
    </div>
    <div v-else class="min-h-screen"></div>
</template>
<script>

export default {
    name: 'UserShow',
    props: {
        id: {
            type: Number
        }
    },
    data() {
        return {
            data: null
        };
    },
    computed: {
        closeDrawer() {
            return this.$store.getters['drawer/getOpen'];
        }
    },
    methods: {
        onClose() {
            this.$store.dispatch('drawer/clearDrawer');
        },
        getDevice() {
            this.$api.getDevice(
                this.id,
                ({data: res}) => {
                    this.data = res.data;
                    this.$store.commit('spinner/toggleSpinning', 'drawer');
                },
                (error) => {
                    this.$store.commit('spinner/toggleSpinning', 'drawer');
                }
            );
        },
        init() {

            if (!this.id) {
                this.onClose();
                this.$store.commit('spinner/toggleSpinning', 'drawer');

                return;
            }

            this.getDevice();
        }
    },
    watch: {
        closeDrawer(newValue) {
            if (!newValue) {
                if (this.$store.getters[`spinner/getDrawerSpinning`]) {
                    this.$store.commit('spinner/toggleSpinning', 'drawer');
                }
                this.data = null
            }
        }
    }
}
</script>
