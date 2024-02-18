<template>
    <Layout>
        <a-table
            :loading="loading"
            :columns="columns"
            :data-source="data"
            :pagination="{ pageSize: 50 }"
            :scroll="{ y: 600 }"
        />
    </Layout>
</template>
<script>

import Layout from "@/pages/Layout.vue";

export default {
    name: 'FactoryIndex',
    components: {Layout},
    data() {
        return {
            columns: [
                {
                    title: 'Nomi',
                    dataIndex: 'name',
                    width: 150
                },
                {
                    title: 'Raqami',
                    dataIndex: 'number',
                    width: 150
                },
                {
                    title: 'Turi',
                    dataIndex: 'type',
                    width: 150
                },
                {
                    title: '',
                    dataIndex: 'actions',
                    width: 150
                }
            ],
            loading: true,
            data: []
        };
    },
    methods: {
        getFactories() {
            this.$api.getFactory(
                ({data}) => {
                    this.data = data.data.map((item, index) => {
                        return {
                            name: item.name,
                            number: item.number,
                            type: item.type,
                            actions: ''
                        };
                    });
                    this.loading = false;
                },
                (response) => {
                    console.log(response);
                    this.loading = false;
                }
            )
        }
    },
    beforeMount() {
        document.title = `${this.$env.appName} | Zavodlar`;
        this.getFactories();
    }
}
</script>
