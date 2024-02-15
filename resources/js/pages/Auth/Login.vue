<template>
    <div class="h-full w-full flex justify-center items-center">
        <div
            class="w-[50rem] h-[30rem] bg-white border-2 bg-gradient-to-bl shadow-2xl rounded-2xl flex flex-col justify-center items-center">
            <p class="text-3xl"><strong>{{ appName }}</strong></p>
            <p class="text-xl mb-10">Вход в систему</p>
            <form class="w-96"
                  @submit.prevent="auth"
                  autocomplete="off">
                <div class="mb-5">
                    <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Логин</label>
                    <input type="text"
                           id="username"
                           v-model="username"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                           placeholder="Введите логин"/>
                </div>
                <div class="mb-5">
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Пароль</label>
                    <input type="password"
                           id="password"
                           v-model="password"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                           placeholder="Введите пароль"/>
                </div>
                <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">
                    Вход
                </button>
            </form>
        </div>
    </div>
</template>
<script>
import getenv from "@/libs/getenv";
import toastr from "toastr";

export default {
    data() {
        return {
            appName: getenv.appName,
            username: null,
            password: null
        };
    },
    methods: {
        auth() {
            if (!this.username) {
                toastr.error("Введите логин");
            }
            if (!this.password) {
                toastr.error("Введите пароль");
            }

            if (this.username && this.password) {
                this.$api.auth(
                    {username: this.username, password: this.password},
                    ({data}) => {
                        this.$store.commit('auth/setToken', data.token);
                        this.$store.commit('auth/setUser', data.user);
                        this.$router.push({name: 'index'});
                    }
                );
            }
        }
    },
    beforeMount() {
        document.title = `${getenv.appName} | Авторизация`;
    }
}
</script>
