<template>
    <Layout page-title="Foydalanuvchilar">
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
        <Drawer key="Drawer" :width="850" :componentKey="componentKey" :component="component"/>
    </Layout>
</template>

<script>
import Layout from "@/pages/Layout.vue";
import Drawer from "@/components/Drawer.vue";
import {markRaw} from "vue";
import UserCreate from "@/pages/Admin/User/UserCreate.vue";
import toastr from "toastr";
import makeSorterField from "@/utils/makeSorterField";
import UserEdit from "@/pages/Admin/User/UserEdit.vue";
import UserShow from "@/pages/Admin/User/UserShow.vue";

export default {
    name: "UserIndex",
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
                    title: 'F.I.Sh.',
                    dataIndex: 'name',
                    width: 200
                },
                {
                    title: 'Zavod',
                    dataIndex: 'factory',
                    width: 50
                },
                {
                    title: 'Sex',
                    dataIndex: 'floor',
                    width: 50,
                },
                {
                    title: '',
                    dataIndex: 'actions',
                    width: 150,
                }
            ],
            componentKey: null,
            loading: true,
            component: markRaw(UserCreate),
            data: [],
        };
    },
    methods: {
        async getUsers(filters) {
            await this.$api.getUsers(
                {page: filters.page, sorter: filters.sorter},
                ({data: res}) => {
                    if (!res.data.length) {
                        this.getUsers({page: res.meta.last_page, sorter: filters.sorter});
                        return;
                    }

                    this.pagination = {
                        total: res.meta.total,
                        current: res.meta.current_page,
                        pageSize: res.meta.per_page,
                    }
                    this.data = res.data.map((item, index) => {
                        return {
                            record: item,
                            orderNumber: index + 1,
                            id: item.id,
                            name: item.name,
                            factory: item.floor ? `${item.floor.factory.name} (${item.floor.factory.number})` : '',
                            floor: item.floor ? `${item.floor.name} (${item.floor.number})` : '',
                            actions: ''
                        };
                    });
                    this.loading = false;

                    this.sorter = filters.sorter;
                    this.$router.push({
                        name: 'user',
                        query: {page: this.pagination.current, sorter: filters.sorter ?? 'id'}
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
            this.$api.deleteUser(
                id,
                ({data}) => {
                    toastr.success(data.message);
                    this.getUsers({page: this.pagination.current, sorter: this.sorter});
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
            this.component = markRaw(UserCreate);
            this.$store.commit('drawer/setDrawerTitle', 'Yangi zavod qo\'shish');
        },
        loadEditComponent(record) {
            this.componentKey = `edit-${record.number}-${record.id}`;
            this.component = markRaw(UserEdit);
            this.$store.commit('drawer/setComponentProps', record);
            this.$store.commit('drawer/setDrawerTitle', 'Zavodni tahrirlash');
            document.getElementById(`record-${record.id}`).closest('tr').classList.add('bg-gray-100');
        },
        loadShowComponent(record) {
            this.componentKey = `show-${record.number}-${record.id}`;
            this.component = markRaw(UserShow);
            this.$store.commit('drawer/setComponentProps', {componentType: record.componentType, id: record.id});
            this.$store.commit('drawer/setDrawerTitle', 'Batafsil');
            document.getElementById(`record-${record.id}`).closest('tr').classList.add('bg-gray-100');
        },
        handleTableChange(page, filters, sorter) {
            this.loading = true;
            this.getUsers({page: page.current, filters, sorter: makeSorterField(sorter.field, sorter.order)});
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
        this.getUsers({page: this.pagination.current, sorter: this.sorter}).then(() => {
            let data = this.$store.getters['drawer/getComponentProps'];
            data && this.openWithComponent(data.componentType, data);
        });
    },
    watch: {
        isReloadPage(newValue) {
            if (newValue) {
                this.loading = true;
                this.getUsers({page: this.pagination.current, sorter: this.sorter});
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

<style scoped>

</style>
