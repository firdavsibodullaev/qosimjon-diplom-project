<template>
    <a-form :model="form"
            :rules="rules"
            autocomplete="off"
            @finish="onFinish"
            @finishFailed="onFinishFailed"
            layout="vertical">
        <a-space class="site-input-group-wrapper w-full" direction="vertical" size="middle">
            <a-input-group>
                <a-row :gutter="8">
                    <a-col class="w-1/2">
                        <a-form-item label="Familiyasi" name="last_name">
                            <a-input v-model:value="form.last_name" placeholder="Familiyasi..."/>
                        </a-form-item>
                    </a-col>
                    <a-col class="w-1/2">
                        <a-form-item label="Ismi" name="first_name">
                            <a-input v-model:value="form.first_name" placeholder="Ismi..."/>
                        </a-form-item>
                    </a-col>
                </a-row>
            </a-input-group>
            <a-input-group>
                <a-row :gutter="8">
                    <a-col class="w-1/2">
                        <a-form-item label="Login" name="username">
                            <a-input
                                autocomplete="new"
                                v-model:value="form.username"
                                placeholder="Login..."/>
                        </a-form-item>
                    </a-col>
                    <a-col class="w-1/2">
                        <a-form-item label="Parol" name="password">
                            <a-input-password
                                autocomplete="new-password"
                                v-model:value="form.password"
                                placeholder="Parol..."/>
                        </a-form-item>
                    </a-col>
                </a-row>
            </a-input-group>
            <a-input-group>
                <a-row :gutter="8">
                    <a-col class="w-1/2">
                        <a-form-item label="Zavod" name="factory_id">
                            <a-select v-model:value="form.factory_id"
                                      @change="getFactoryFloor"
                                      placeholder="Zavod...">
                                <a-select-option v-for="(factory, index) in factories"
                                                 :key="`factory-type-${index}-${factory.id}`"
                                                 :value="factory.id">{{ factory.name }} ({{ factory.number }})
                                </a-select-option>
                            </a-select>
                        </a-form-item>
                    </a-col>
                    <a-col class="w-1/2">
                        <a-form-item label="Sex" name="factory_floor_id">
                            <a-select v-model:value="form.factory_floor_id"
                                      mode="multiple"
                                      placeholder="Sex...">
                                <a-select-option v-for="(floor, index) in floors"
                                                 :key="`factory-type-${index}-${floor.id}`"
                                                 :value="floor.id">{{ floor.name }} ({{ floor.number }})
                                </a-select-option>
                            </a-select>
                        </a-form-item>
                    </a-col>
                </a-row>
            </a-input-group>
            <a-col class="w-full">
                <a-form-item label="Rol" name="role">
                    <a-select v-model:value="form.role" placeholder="Rol...">
                        <a-select-option v-for="(role, key) in roles"
                                         :key="`factory-type-${key}`"
                                         :value="key">{{ role }}
                        </a-select-option>
                    </a-select>
                </a-form-item>
            </a-col>
            <a-col>
                <a-form-item>
                    <a-button type="primary" class="bg-ant-primary" html-type="submit">Saqlash</a-button>
                </a-form-item>
            </a-col>
        </a-space>
    </a-form>
</template>
<script>

import toastr from "toastr";
import showValidationErrors from "@/utils/showValidationErrors";
import roles from "@/pages/Admin/User/roles";

export default {
    name: 'UserCreate',
    data() {
        return {
            form: {
                last_name: null,
                first_name: null,
                username: null,
                password: null,
                factory_id: null,
                factory_floor_id: [],
                role: null
            },
            rules: {
                last_name: [{required: true, message: 'Familiyani kiriting'}],
                first_name: [{required: true, message: 'Ismini kiriting'}],
                username: [{required: true, message: 'Loginni kiriting'}],
                password: [{required: true, message: 'Parolni kiriting'}],
                role: [{required: true, message: 'Rolni kiriting'}],
            },
            factories: [],
            floors: [],
            roles: roles.forRole(this.currentRole)
        };
    },
    computed: {
        closeDrawer() {
            return this.$store.getters['drawer/getOpen'];
        },
        currentRole() {
            return this.$store.getters['auth/getUser'].roles[0].key;
        }
    },
    methods: {
        init() {
            this.getFactories()
                .then(() => this.$store.commit('spinner/toggleSpinning', 'drawer'));
        },
        async getFactories() {
            await this.$api.getFactories({list: 1}, ({data}) => this.factories = data.data);
        },
        onClose() {
            this.$store.dispatch('drawer/clearDrawer');
        },
        onFinish(payload) {
            this.$store.commit('spinner/toggleSpinning', 'main');
            this.$api.createUser(
                payload,
                ({data}) => {
                    toastr.success(data.message);
                    this.$store.commit('spinner/toggleSpinning', 'main');
                    this.$store.commit('factory/setIsReload', true);
                    this.onClose();
                },
                ({response}) => {
                    this.$store.commit('spinner/toggleSpinning', 'main');
                    if (response.status === 422) {
                        showValidationErrors(response.data.errors);
                    }
                });
        },
        onFinishFailed(errors) {
            console.log(errors);
        },
        getFactoryFloor(value) {
            this.form.factory_floor_id = [];
            this.$api.getFactoryFloors({factory_id: value, list: 1}, ({data: res}) => this.floors = res.data);
        }
    },
    watch: {
        closeDrawer(newValue) {
            if (!newValue) {
                this.form = {
                    last_name: null,
                    first_name: null,
                    username: null,
                    password: null,
                    factory_id: null,
                    factory_floor_id: [],
                    role: null
                };
            }
        }
    }
}
</script>
