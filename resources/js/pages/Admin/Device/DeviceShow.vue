<template>
    <div class="mt-4" v-if="data">
        <div class="grid grid-cols-2 gap-6 mb-4">
            <div class="text-lg"><strong>F.I.Sh.:</strong></div>
            <div class="text-lg">{{ data.name }}</div>
        </div>
        <div class="grid grid-cols-2 gap-6 mb-4">
            <div class="text-lg"><strong>Zavod:</strong></div>
            <div class="text-lg">{{ factory ?? '-' }}</div>
        </div>
        <div class="grid grid-cols-2 gap-6 mb-4">
            <div class="text-lg"><strong>Sex:</strong></div>
            <div class="text-lg">{{ floor ?? '-' }}</div>
        </div>
        <div class="grid grid-cols-2 gap-6 mb-4">
            <div class="text-lg"><strong>Rol:</strong></div>
            <div class="text-lg">{{ role ?? '-' }}</div>
        </div>
        <hr>
    </div>
    <div v-else class="min-h-screen"></div>
</template>
<script>

import roles from "@/pages/Admin/User/roles";

export default {
    name: 'UserShow',
    props: {
        id: {
            type: Number
        }
    },
    data() {
        return {
            data: null,
            roles
        };
    },
    computed: {
        closeDrawer() {
            return this.$store.getters['drawer/getOpen'];
        },
        role() {
            return this.data.roles.length ? roles[this.data.roles[0].key] : null;
        },
        factory() {
          return this.data.floor ? `${this.data.floor.factory.name} (${this.data.floor.factory.number})` : null;
        },
        floor() {
            return this.data.floor ? `${this.data.floor.name} (${this.data.floor.number})` : null;
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
