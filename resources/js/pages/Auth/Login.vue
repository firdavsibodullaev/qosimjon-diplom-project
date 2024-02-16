<template>
    <GuestLayout>
        <div class="bg-gray-200 h-[100vh] w-[100vw] flex justify-center items-center">
            <div
                class="w-[40rem] bg-white p-16 rounded-2xl shadow-current flex flex-col items-center content-center justify-center">
                <p class="w-full text-4xl text-center mb-2"><strong>{{ $env.appName }}</strong></p>
                <p class="w-full text-sm text-center mb-6">Вход в систему</p>
                <Spinner :spinning="spinning">
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
                </Spinner>
            </div>
        </div>
    </GuestLayout>
</template>
<script>
import {reactive} from "vue";
import toastr from "toastr";
import GuestLayout from "@/pages/GuestLayout.vue";
import Spinner from "@/components/Spinner.vue";

export default {
    components: {Spinner, GuestLayout},
    data() {
        return {
            spinning: false,
            formState: reactive({
                username: '',
                password: ''
            })
        };
    },
    methods: {
        onFinish(credentials) {
            this.spinning = true;
            this.$api.auth(
                credentials,
                ({data}) => {
                    this.$store.commit('auth/setToken', data.token);
                    this.$store.commit('auth/setUser', data.user);

                    this.spinning = false;
                    this.$router.push({name: 'index'});
                },
                (error) => {
                    console.log(error);
                    switch (error.status) {
                        case 401:
                            toastr.error(error.data.message);
                            break;
                        case 422:
                            Object.keys(error.data.errors).forEach(key => toastr.error(error.data.errors[key][0]))
                            break;
                    }
                    this.spinning = false;
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
