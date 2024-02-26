<template>
    <div class="mt-4" v-if="data">
        <div class="grid grid-cols-2 gap-6 mb-4">
            <div class="text-lg"><strong>Nomi:</strong></div>
            <div class="text-lg">{{ data.name }}</div>
        </div>
        <div class="grid grid-cols-2 gap-6 mb-4">
            <div class="text-lg"><strong>Raqami:</strong></div>
            <div class="text-lg">{{ data.number }}</div>
        </div>
        <div class="grid grid-cols-2 gap-6 mb-4">
            <div class="text-lg"><strong>Zavod:</strong></div>
            <div :class="{'text-red-600': data.factory.is_deleted}"
                class="text-lg">
                {{ data.factory.name }} ({{ data.factory.number }})
                <span v-if="data.factory.is_deleted" class="text-sm">(O'chirilgan)</span>
            </div>
        </div>
        <hr>
        <div class="mt-10">
            <p class="text-xl"><strong>Ishchilar</strong></p>
            <a-table
                :columns="columns"
                :pagination="false"
                :data-source="users"
                :scroll="{ y: 430 }"
            />
        </div>
    </div>
    <div v-else class="min-h-screen"></div>
</template>
<script>

export default {
    name: 'FactoryFloorShow',
    props: {
        id: {
            type: Number
        }
    },
    data() {
        return {
            columns: [
                {
                    title: '№',
                    dataIndex: 'orderNumber',
                    width: 20
                },
                {
                    title: 'F.I.Sh.',
                    dataIndex: 'name',
                    width: 150
                }
            ],
            data: null
        };
    },
    computed: {
        closeDrawer() {
            return this.$store.getters['drawer/getOpen'];
        },
        users() {
            return this.data.users.map((user, index) => {
                return {orderNumber: index + 1, name: user.name};
            });
        }
    },
    methods: {
        onClose() {
            this.$store.dispatch('drawer/clearDrawer');
        },
        getFactoryFloor() {
            this.$api.getFactoryFloor(
                this.id,
                ({data}) => {
                    this.data = data.data;
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

            this.getFactoryFloor();
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
