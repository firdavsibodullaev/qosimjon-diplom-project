<template>
    <Spinner>
        <a-layout style="height: 100vh">
            <a-layout-sider v-model:collapsed="$store.getters['sidebar/getCollapsed']" :trigger="null" collapsible>
                <div class="logo"/>
                <a-menu v-model:selectedKeys="selectedKeys"
                        theme="dark"
                        v-model:openKeys="openKeys"
                        mode="inline">
                    <a-menu-item key="1">
                        <pie-chart-outlined/>
                        <span>Option 1</span>
                    </a-menu-item>
                    <a-menu-item key="2">
                        <desktop-outlined/>
                        <span>Option 2</span>
                    </a-menu-item>
                    <a-sub-menu key="sub1">
                        <template #title>
                        <span>
                          <user-outlined/>
                          <span>User</span>
                        </span>
                        </template>
                        <a-menu-item key="3">Tom</a-menu-item>
                        <a-menu-item key="4">Bill</a-menu-item>
                        <a-menu-item key="5">Alex</a-menu-item>
                    </a-sub-menu>
                    <a-sub-menu key="sub2">
                        <template #title>
                    <span>
                      <team-outlined/>
                      <span>Team</span>
                    </span>
                        </template>
                        <a-menu-item key="6">Team 1</a-menu-item>
                        <a-menu-item key="8">Team 2</a-menu-item>
                    </a-sub-menu>
                    <a-menu-item key="9">
                        <file-outlined/>
                        <span>File</span>
                    </a-menu-item>
                </a-menu>
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
                                    <a-menu-item @click="logout">Выход из системы</a-menu-item>
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
import {ref} from 'vue';
import Spinner from "@/components/Spinner.vue";

export default {
    name: 'Layout',
    components: {Spinner},
    data() {
        return {
            selectedKeys: ref(['1']),
            collapsed: false,
            openKeys: ['sub1']
        };
    },
    methods: {
        logout() {
            this.$store.commit('spinner/toggleSpinning');
            this.$api.logout((response) => {
                this.$store.commit('auth/setToken', null);
                this.$store.commit('auth/setUser', null);

                this.$store.commit('spinner/toggleSpinning');

                this.$router.push({name: "login"});
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
