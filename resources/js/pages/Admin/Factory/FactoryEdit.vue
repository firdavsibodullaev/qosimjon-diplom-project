<template>
    <a-form :model="form"
            :rules="rules"
            autocomplete="off"
            @finish="onFinish"
            @finishFailed="onFinishFailed"
            layout="vertical">
        <a-col>
            <a-form-item label="Zavod nomi" name="name">
                <a-input v-model:value="form.name" placeholder="Zavod nomi..."/>
            </a-form-item>
        </a-col>
        <a-col>
            <a-form-item label="Zavod raqami" name="number">
                <a-input v-model:value="form.number" placeholder="Zavod raqami..."/>
            </a-form-item>
        </a-col>
        <a-col>
            <a-form-item label="Zavod turi" name="type">
                <a-select v-model:value="form.type" placeholder="Zavod turi...">
                    <a-select-option value="giving_for_test">Arizachi</a-select-option>
                    <a-select-option value="tester">Tekshiruvchi</a-select-option>
                </a-select>
            </a-form-item>
        </a-col>
        <a-col>
            <a-form-item>
                <a-button type="primary" class="bg-ant-primary" html-type="submit">Saqlash</a-button>
            </a-form-item>
        </a-col>
    </a-form>
</template>
<script>

import toastr from "toastr";

export default {
    name: 'FactoryEdit',
    props: {
        id: {
            type: Number,
        },
        name: {
            type: String,
        },
        number: {
            type: Number,
        },
        type: {
            type: String,
        },
    },
    data() {
        return {
            isActive: this.$store.getters['drawer/getOpen'],
            form: {
                name: this.name,
                number: this.number,
                type: this.type
            },
            rules: {
                name: [{
                    required: true,
                    message: 'Zavod nomini kiriting'
                }],
                number: [{
                    required: true,
                    message: 'Zavod raqamini kiriting'
                }],
                type: [{
                    required: true,
                    message: 'Zavod turini kiriting'
                }],
            }
        };
    },
    computed: {
        closeDrawer() {
            return this.$store.getters['drawer/getOpen'];
        }
    },
    methods: {
        onClose() {
            this.$store.dispatch('drawer/clearDrawer');
        },
        onFinish(payload) {
            this.$store.commit('spinner/toggleSpinning');
            this.$api.updateFactory(
                this.id,
                payload,
                ({data}) => {
                    toastr.success(data.message);
                    this.$store.commit('spinner/toggleSpinning');
                    this.$store.commit('factory/setIsReload', true);
                    this.onClose();
                },
                error => {
                    console.log(error);
                }
            )
            ;
        },
        onFinishFailed(errors) {
            this.$store.commit('spinner/toggleSpinning');
            console.log(errors);
        },
    },
    watch: {
        closeDrawer(newValue) {

            this.form = newValue ? {name: this.name, number: this.number, type: this.type}
                : {name: null, number: null, type: null};
        }
    }
}
</script>
