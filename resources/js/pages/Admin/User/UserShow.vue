<template>
    <div class="mt-4" v-if="data">
        <div class="grid grid-cols-2 gap-6 my-4">
            <div class="text-lg"><strong>F.I.Sh.:</strong></div>
            <div class="text-lg">{{ data.name }}</div>
        </div>
        <hr>
        <div class="grid grid-cols-2 gap-6 my-4">
            <div class="text-lg"><strong>Zavod:</strong></div>
            <div class="text-lg">{{ factory ? factory : '-' }}</div>
        </div>
        <hr>
        <div class="grid grid-cols-2 gap-6 my-4">
            <div class="text-lg"><strong>Sex:</strong></div>
            <div class="text-lg">
                <div v-if="floors.length">
                    <div :key="`factory-floor-${index}`"
                         v-for="(floor, index) in floors">
                        <p class="py-2">{{ floor }}</p>
                        <hr v-if="index !== floors.length - 1">
                    </div>
                </div>
                <div v-else>-</div>
            </div>
        </div>
        <hr>
        <div class="grid grid-cols-2 gap-6 my-4">
            <div class="text-lg"><strong>Rol:</strong></div>
            <div class="text-lg">{{ role ?? '-' }}</div>
        </div>
        <hr>
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
        },
        role() {
            return this.data.roles.length ? this.data.roles[0].text : null;
        },
        factory() {
            return this.data.floors.map(floor => `${floor.factory.name} (${floor.factory.number})`).unique().join(', ')
        },
        floors() {
            return this.data.floors.map(floor => `${floor.name} (${floor.number})`).unique()
        }
    },
    methods: {
        onClose() {
            this.$store.dispatch('drawer/clearDrawer');
        },
        getUser() {
            this.$api.getUser(
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

            this.getUser();
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
