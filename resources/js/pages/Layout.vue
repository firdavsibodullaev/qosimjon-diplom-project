<template>
    <Spinner type="main">
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
                    <div class="flex items-center content-center">
                        <MenuUnfoldOutlined
                            v-if="$store.getters['sidebar/getCollapsed']"
                            class="trigger"
                            @click="() => $store.commit('sidebar/setCollapsed')"
                        />
                        <MenuFoldOutlined v-else class="trigger" @click="() => $store.commit('sidebar/setCollapsed')"/>
                        <p class="text-xl"><strong>{{ $env.appName }}</strong></p>
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
                <a-layout-content class="my-[24px] mx-[8px] p-[12px] bg-white min-h-[280px]">
                    <p class="text-xl mb-8"><strong>{{ pageTitle }}</strong></p>
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
import {DownOutlined, MenuFoldOutlined, MenuUnfoldOutlined} from "@ant-design/icons-vue";

export default {
    name: 'Layout',
    components: {Sidebar, Spinner, MenuUnfoldOutlined, MenuFoldOutlined, DownOutlined},
    data() {
        return {
            collapsed: false,
        };
    },
    props: {
        pageTitle: {
            type: String,
            required: true
        }
    },
    methods: {
        logout() {
            this.$store.commit('spinner/toggleSpinning', 'main');

            this.$api.logout((response) => {

                this.$store.commit('spinner/toggleSpinning', 'main');
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
