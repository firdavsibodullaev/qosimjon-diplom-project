<template>
    <AuthLayout>
        <div class="bg-[#f5f5f5] h-[100vh] w-[100vw] flex justify-center items-center">
            <div
                class="w-[40rem] bg-white p-16 rounded-2xl shadow-current flex flex-col items-center content-center justify-center border-[#f5f5f5] border-t-ant-primary border-2">
                <p class="w-full text-xl text-center mb-1"><strong>{{ $env.appName }}</strong></p>
                <p class="w-full text-lg text-center mb-6 text-gray-600">Tizimga kirish</p>
                <Spinner type="main">
                    <a-form
                        class="w-[300px]"
                        :model="formState"
                        :rules="rules"
                        name="basic"
                        autocomplete="off"
                        @finish="onFinish"
                        @finishFailed="onError"
                        layout="vertical"
                    >
                        <a-form-item
                            label="Login"
                            name="username"
                        >
                            <a-input
                                autocomplete="new"
                                placeholder="Login..."
                                v-model:value="formState.username"/>
                        </a-form-item>

                        <a-form-item
                            label="Parol"
                            name="password"
                        >
                            <a-input-password
                                autocomplete="new-password"
                                placeholder="Parol..." v-model:value="formState.password"/>
                        </a-form-item>

                        <a-form-item class="flex w-full justify-center">
                            <a-button class="bg-ant-primary" type="primary" html-type="submit">Kirish</a-button>
                        </a-form-item>
                    </a-form>
                </Spinner>
            </div>
        </div>
    </AuthLayout>
</template>
<script>
import toastr from "toastr";
import AuthLayout from "@/pages/AuthLayout.vue";
import Spinner from "@/components/Spinner.vue";
import showValidationErrors from "@/utils/showValidationErrors";

export default {
    components: {Spinner, AuthLayout},
    data() {
        return {
            formState: {
                username: '',
                password: ''
            },
            rules: {
                username: [{required: true, message: 'Loginni kiriting!'}],
                password: [{required: true, message: 'Parolni kiriting!'}]
            }
        };
    },
    methods: {
        onFinish(credentials) {
            this.$store.commit('spinner/toggleSpinning', 'main');
            this.$api.auth(
                credentials,
                ({data}) => {
                    this.$store.commit('auth/setToken', data.data.token);
                    this.$store.commit('auth/setUser', data.data.user);

                    this.$store.commit('spinner/toggleSpinning', 'main');
                    this.$router.push({name: 'index'});
                    toastr.success(data.message);
                },
                ({response}) => {
                    switch (response.status) {
                        case 401:
                            toastr.error(response.data.message);
                            break;
                        case 422:
                            showValidationErrors(response.data.errors);
                            break;
                    }
                    this.$store.commit('spinner/toggleSpinning', 'main');
                }
            );
        },
        onError(error) {

        }
    },
    beforeMount() {
        document.title = `${this.$env.appName} | Kirish`;
    }
}
</script>
