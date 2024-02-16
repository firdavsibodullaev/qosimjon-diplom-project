<template>
    <GuestLayout>
        <div class="bg-gray-200 h-[100vh] w-[100vw] flex justify-center items-center">
            <div class="w-[40rem] bg-white p-16 rounded-2xl shadow-current flex flex-col items-center content-center justify-center">
                <p class="w-full text-4xl text-center mb-2"><strong>{{ $env.appName}}</strong></p>
                <p class="w-full text-sm text-center mb-6">Вход в систему</p>
                <a-form
                    class="w-full"
                    :model="formState"
                    name="basic"
                    autocomplete="off"
                    @finish="onFinish"
                    @finishFailed="onError"
                    layout="vertical"
                >
                    <a-form-item
                        label="Логин"
                        name="username"
                        :rules="[{ required: true, message: 'Введите логин!' }]"
                    >
                        <a-input v-model:value="formState.username"/>
                    </a-form-item>

                    <a-form-item
                        label="Пароль"
                        name="password"
                        :rules="[{ required: true, message: 'Введите пароль!' }]"
                    >
                        <a-input-password v-model:value="formState.password"/>
                    </a-form-item>

                    <a-form-item class="flex w-full justify-center">
                        <a-button class="bg-[#1677ff]" type="primary" html-type="submit">Вход</a-button>
                    </a-form-item>
                </a-form>
            </div>
        </div>
    </GuestLayout>
</template>
<script>
import {reactive} from "vue";
import GuestLayout from "@/pages/GuestLayout.vue";

export default {
    components: {GuestLayout},
    data() {
        return {
            formState: reactive({
                username: '',
                password: ''
            })
        };
    },
    methods: {
        onFinish(credentials) {
            this.$api.auth(
                credentials,
                ({data}) => {
                    this.$store.commit('auth/setToken', data.token);
                    this.$store.commit('auth/setUser', data.user);
                    this.$router.push({name: 'index'});
                }
            );
        },
        onError(error) {

        }
    },
    beforeMount() {
        document.title = `${this.$env.appName} | Авторизация`;
    }
}
</script>
