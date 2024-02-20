<template>
    <Layout>
        <a-button class="bg-ant-primary mb-4"
                  type="primary" @click="openWithComponent(null)">Yangi qo'shish
        </a-button>
        <a-table
            :loading="loading"
            :columns="columns"
            :data-source="data"
            :pagination="pagination"
            :scroll="{ y: 600 }"
            @change="handleTableChange"
        >
            <template #bodyCell="{column, text, record}">
                <template v-if="column.dataIndex === 'actions'">
                    <a-button type="primary"
                              :id="`record-${record.record.id}`"
                              @click="this.openWithComponent(record.record)"
                              class="mr-2 bg-ant-primary">Tahrirlash
                    </a-button>
                    <a-popconfirm
                        v-if="data.length"
                        title="Ma'umotni o'chirmoqchimisiz?"
                    >
                        <template #okButton>
                            <a-button
                                @click="onDelete(record.id)"
                                danger
                                type="primary">O'chirish
                            </a-button>
                        </template>
                        <a-button type="primary" danger>O'chirish</a-button>
                    </a-popconfirm>
                </template>
            </template>
        </a-table>
        <Drawer key="Drawer" :componentKey="componentKey" :component="component"/>
    </Layout>
</template>
<script>

import Layout from "@/pages/Layout.vue";
import toastr from "toastr";
import Drawer from "@/components/Drawer.vue";
import FactoryEdit from "@/pages/Admin/Factory/FactoryEdit.vue";
import FactoryCreate from "@/pages/Admin/Factory/FactoryCreate.vue";
import {markRaw} from "vue";

export default {
    name: 'FactoryIndex',
    components: {FactoryEdit, FactoryCreate, Drawer, Layout},
    data() {
        return {
            pagination: {
                total: 0,
                current: this.$route.query.page ?? 1,
                pageSize: 0,
            },
            columns: [
                {
                    title: '№',
                    dataIndex: 'orderNumber',
                    width: 20
                },
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
                    width: 150,
                }
            ],
            componentKey: null,
            loading: true,
            component: markRaw(FactoryCreate),
            data: [],
        };
    },
    methods: {
        async getFactories(filters) {
            let types = {
                "giving_for_test": "Arizachi",
                "tester": "Tekshiruvchi"
            };
            await this.$api.getFactory(
                {page: filters.page},
                ({data}) => {
                    if (!data.data.length) {
                        this.getFactories({page: data.meta.last_page});
                        return;
                    }

                    this.pagination = {
                        total: data.meta.total,
                        current: data.meta.current_page,
                        pageSize: data.meta.per_page,
                    }
                    this.data = data.data.map((item, index) => {
                        return {
                            record: item,
                            orderNumber: index + 1,
                            id: item.id,
                            name: item.name,
                            number: item.number,
                            type: types[item.type],
                            actions: ''
                        };
                    });
                    this.loading = false;
                    this.$router.push({name: 'factory', query: {page: this.pagination.current}});
                },
                (response) => {
                    console.log(response);
                    this.loading = false;
                }
            )
        },
        onDelete(id) {
            this.loading = true;
            this.$api.deleteFactory(
                id,
                ({data}) => {
                    toastr.success(data.message);
                    this.getFactories({page: this.pagination.current});
                },
                (error) => {
                    this.loading = false;
                }
            );
        },
        openDrawer() {
            this.$store.commit('drawer/setOpen', true);
        },
        openWithComponent(record = null) {
            record
                ? this.loadEditComponent(record)
                : this.loadCreateComponent();
            this.openDrawer();
        },
        loadCreateComponent() {
            this.componentKey = `create`;
            this.component = markRaw(FactoryCreate);
            this.$store.commit('drawer/setDrawerTitle', 'Yangi zavod qo\'shish');
        },
        loadEditComponent(record) {
            this.componentKey = `edit-${record.number}-${record.id}`;
            this.component = markRaw(FactoryEdit);
            this.$store.commit('drawer/setComponentProps', record);
            this.$store.commit('drawer/setDrawerTitle', 'Zavodni tahrirlash');
            document.getElementById(`record-${record.id}`).closest('tr').classList.add('bg-gray-100');
        },
        handleTableChange(page, filters, sorter) {
            this.loading = true;
            this.getFactories({page: page.current, filters, sorter});
        }
    },
    computed: {
        isReloadPage() {
            return this.$store.getters['factory/getIsReload'];
        },
        drawerStatus() {
            return this.$store.getters['drawer/getOpen'];
        }
    },
    beforeMount() {
        document.title = `${this.$env.appName} | Zavodlar`;
        this.getFactories({page: this.pagination.current}).then(() => {
            let data = this.$store.getters['drawer/getComponentProps'];
            data && this.openWithComponent(data);
        });
    },
    watch: {
        isReloadPage(newValue) {
            if (newValue) {
                this.getFactories({page: this.pagination.current});
                this.$store.commit('factory/setIsReload', false);
            }
        },
        drawerStatus(newValue) {
            if (!newValue) {
                document.querySelectorAll('tr.bg-gray-100')
                    .forEach((dom) => dom.classList.remove('bg-gray-100'));
            }
        }
    }
}
</script>
