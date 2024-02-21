<template>
    <Layout>
        <p class="text-4xl mb-8"><strong>Sexlar</strong></p>
        <a-button class="bg-ant-primary mb-4"
                  type="primary" @click="openWithComponent('create',null)">Yangi qo'shish
        </a-button>
        <a-table
            :loading="loading"
            :columns="columns"
            :data-source="data"
            :pagination="pagination"
            :scroll="{ y: 530 }"
            @change="handleTableChange"
        >
            <template #bodyCell="{column, text, record}">
                <template v-if="column.dataIndex === 'actions'">
                    <a-button type="primary"
                              @click="this.openWithComponent('show',record.record)"
                              class="mr-2 bg-ant-primary">Batafsil
                    </a-button>
                    <a-button type="primary"
                              :id="`record-${record.record.id}`"
                              @click="this.openWithComponent('edit',record.record)"
                              class="mr-2 bg-yellow-400 text-black hover:!bg-yellow-300 hover:!text-black">Tahrirlash
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
import {markRaw} from "vue";
import FactoryFloorCreate from "@/pages/Admin/FactoryFloor/FactoryFloorCreate.vue";
import Drawer from "@/components/Drawer.vue";
import FactoryFloorEdit from "@/pages/Admin/FactoryFloor/FactoryFloorEdit.vue";
import FactoryFloorShow from "@/pages/Admin/FactoryFloor/FactoryFloorShow.vue";
import makeSorterField from "@/utils/makeSorterField";

export default {
    name: "FactoryFloorIndex",
    components: {Drawer, Layout},
    data() {
        return {
            pagination: {
                total: 0,
                current: this.$route.query.page ?? 1,
                pageSize: 0,
            },
            sorter: this.$route.query.sorter ?? 'id',
            columns: [
                {
                    title: '№',
                    dataIndex: 'orderNumber',
                    width: 20
                },
                {
                    title: 'ID',
                    dataIndex: 'id',
                    width: 20,
                    sorter: true
                },
                {
                    title: 'Nomi',
                    dataIndex: 'name',
                    width: 200
                },
                {
                    title: 'Raqami',
                    dataIndex: 'number',
                    width: 50,
                    sorter: true
                },
                {
                    title: 'Zavod',
                    dataIndex: 'factory',
                    width: 50
                },
                {
                    title: '',
                    dataIndex: 'actions',
                    width: 150,
                }
            ],
            componentKey: null,
            loading: true,
            component: markRaw(FactoryFloorCreate),
            data: [],
        };
    },
    computed: {
        isReloadPage() {
            return this.$store.getters['factory/getIsReload'];
        },
        drawerStatus() {
            return this.$store.getters['drawer/getOpen'];
        }
    },
    methods: {
        async getFactoryFloors(filters) {
            await this.$api.getFactoryFloors(
                {page: filters.page, sorter: filters.sorter},
                ({data}) => {
                    if (!data.data.length) {
                        this.getFactoryFloors({page: data.meta.last_page, sorter: filters.sorter});
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
                            factory: item.factory.name,
                            actions: ''
                        };
                    });
                    this.loading = false;
                    this.$router.push({
                        name: 'floor',
                        query: {page: this.pagination.current, sorter: filters.sorter ?? 'number'}
                    });
                },
                (response) => {
                    console.log(response);
                    this.loading = false;
                }
            )
        },
        onDelete(id) {
            this.loading = true;
            this.$api.deleteFactoryFloor(
                id,
                ({data}) => {
                    toastr.success(data.message);
                    this.getFactoryFloors({page: this.pagination.current, sorter: this.sorter});
                },
                (error) => {
                    this.loading = false;
                }
            );
        },
        openDrawer() {
            this.$store.commit('drawer/setOpen', true);
        },
        openWithComponent(type, record = null) {
            switch (type) {
                case "create":
                    this.loadCreateComponent();
                    break;
                case "edit":
                    record.componentType = "edit";
                    this.loadEditComponent(record);
                    break;
                case "show":
                    record.componentType = "show";
                    this.loadShowComponent(record);
                    break;
                default:
                    console.error("Выбрали неправильный тип");
                    return;
            }
            this.openDrawer();
        },
        loadCreateComponent() {
            this.componentKey = `create`;
            this.component = markRaw(FactoryFloorCreate);
            this.$store.commit('drawer/setDrawerTitle', 'Yangi sex qo\'shish');
        },
        loadEditComponent(record) {
            this.componentKey = `edit-${record.number}-${record.id}`;
            this.component = markRaw(FactoryFloorEdit);
            this.$store.commit('drawer/setComponentProps', record);
            this.$store.commit('drawer/setDrawerTitle', 'Sexni tahrirlash');
            document.getElementById(`record-${record.id}`).closest('tr').classList.add('bg-gray-100');
        },
        loadShowComponent(record) {
            this.componentKey = `show-${record.number}-${record.id}`;
            this.component = markRaw(FactoryFloorShow);
            this.$store.commit('drawer/setComponentProps', {componentType: record.componentType, id: record.id});
            this.$store.commit('drawer/setDrawerTitle', 'Batafsil');
            document.getElementById(`record-${record.id}`).closest('tr').classList.add('bg-gray-100');
        },
        handleTableChange(page, filters, sorter) {
            this.loading = true;
            console.log(sorter);
            this.getFactoryFloors({page: page.current, filters, sorter: makeSorterField(sorter.field, sorter.order)});
        }
    },
    beforeMount() {
        document.title = `${this.$env.appName} | Sexlar`;
        this.getFactoryFloors({page: this.pagination.current, sorter: this.sorter})
            .then(() => {
                let data = this.$store.getters['drawer/getComponentProps'];
                data && this.openWithComponent(data.componentType, data);
            });
    },
    watch: {
        isReloadPage(newValue) {
            if (newValue) {
                this.loading = true;
                this.getFactoryFloors({page: this.pagination.current, sorter: this.sorter});
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
