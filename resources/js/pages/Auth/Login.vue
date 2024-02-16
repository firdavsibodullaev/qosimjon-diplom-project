<template>
    <AuthLayout>
        <div class="bg-gray-200 h-[100vh] w-[100vw] flex justify-center items-center">
            <div
                class="w-[40rem] bg-white p-16 rounded-2xl shadow-current flex flex-col items-center content-center justify-center border-t-ant-primary border-4">
                <p class="w-full text-4xl text-center mb-1"><strong>{{ $env.appName }}</strong></p>
                <p class="w-full text-sm text-center mb-6 text-gray-600">Вход в систему</p>
                <Spinner>
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
                            :rules="[{ required: true, message: 'Введите Логин!' }]"
                        >
                            <a-input placeholder="Введите логин" v-model:value="formState.username"/>
                        </a-form-item>

                        <a-form-item
                            label="Пароль"
                            name="password"
                            :rules="[{ required: true, message: 'Введите пароль!' }]"
                        >
                            <a-input-password placeholder="Введите пароль" v-model:value="formState.password"/>
                        </a-form-item>

                        <a-form-item class="flex w-full justify-center">
                            <a-button class="bg-ant-primary" type="primary" html-type="submit">Вход</a-button>
                        </a-form-item>
                    </a-form>
                </Spinner>
            </div>
        </div>
    </AuthLayout>
</template>
<script>
import {reactive} from "vue";
import toastr from "toastr";
import AuthLayout from "@/pages/AuthLayout.vue";
import Spinner from "@/components/Spinner.vue";

export default {
    components: {Spinner, AuthLayout},
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
            this.$store.commit('spinner/toggleSpinning');
            this.$api.auth(
                credentials,
                ({data}) => {
                    this.$store.commit('auth/setToken', data.token);
                    this.$store.commit('auth/setUser', data.user);

                    this.$store.commit('spinner/toggleSpinning');
                    this.$router.push({name: 'index'});
                },
                (error) => {
                    switch (error.status) {
                        case 401:
                            toastr.error(error.data.message);
                            break;
                        case 422:
                            Object.keys(error.data.errors).forEach(key => toastr.error(error.data.errors[key][0]))
                            break;
                    }
                    this.$store.commit('spinner/toggleSpinning');
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
