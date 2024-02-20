<template>
    <Spinner>
        <a-layout class="min-h-screen">
            <a-layout-sider v-model:collapsed="$store.getters['sidebar/getCollapsed']"
                            class="overflow-y-auto"
                            :trigger="null"
                            collapsible>
                <div class="logo"/>
                <Sidebar/>
            </a-layout-sider>
            <a-layout>
                <a-layout-header style="background: #fff; padding: 0" class="flex justify-between items-center">
                    <div>
                        <MenuUnfoldOutlined
                            v-if="$store.getters['sidebar/getCollapsed']"
                            class="trigger"
                            @click="() => $store.commit('sidebar/setCollapsed')"
                        />
                        <MenuFoldOutlined v-else class="trigger" @click="() => $store.commit('sidebar/setCollapsed')"/>
                    </div>
                    <div class="mr-5">
                        <a-dropdown :trigger="['click']">
                            <a class="ant-dropdown-link" @click.prevent>
                                {{ $store.getters['auth/getUser']?.name }}
                                <DownOutlined/>
                            </a>
                            <template #overlay>
                                <a-menu>
                                    <a-menu-divider/>
                                    <a-menu-item @click="logout">Tizimdan chiqish</a-menu-item>
                                </a-menu>
                            </template>
                        </a-dropdown>
                    </div>
                </a-layout-header>
                <a-layout-content
                    :style="{ margin: '24px 16px', padding: '24px', background: '#fff', minHeight: '280px' }"
                >
                    <slot/>
                </a-layout-content>
            </a-layout>
        </a-layout>
    </Spinner>
</template>
<script>
import Spinner from "@/components/Spinner.vue";
import toastr from "toastr";
import Sidebar from "@/pages/Sidebar.vue";

export default {
    name: 'Layout',
    components: {Sidebar, Spinner},
    data() {
        return {
            collapsed: false,
        };
    },
    methods: {
        logout() {
            this.$store.commit('spinner/toggleSpinning');

            this.$api.logout((response) => {

                this.$store.commit('spinner/toggleSpinning');
                if (response.status === 200) {
                    this.$store.commit('auth/setToken', null);
                    this.$store.commit('auth/setUser', null);

                    this.$router.push({name: "login"});

                    toastr.success(response.data.message)
                }
            });
        }
    }
}
</script>
<style scoped>
.trigger {
    font-size: 18px;
    line-height: 64px;
    padding: 0 24px;
    cursor: pointer;
    transition: color 0.3s;
}

.trigger:hover {
    color: #1890ff;
}

.logo {
    height: 32px;
    background: rgba(255, 255, 255, 0.3);
    margin: 16px;
}

.site-layout .site-layout-background {
    background: #fff;
}
</style>
