<template>
    <a-form :model="form"
            :rules="rules"
            autocomplete="off"
            @finish="onFloorFinish"
            @finishFailed="onFloorFinishFailed"
            layout="vertical">
        <a-col>
            <a-form-item label="Sex nomi" name="name">
                <a-input v-model:value="form.name" placeholder="Sex nomi..."/>
            </a-form-item>
        </a-col>
        <a-col>
            <a-form-item label="Sex raqami" name="number">
                <a-input v-model:value="form.number" placeholder="Sex raqami..."/>
            </a-form-item>
        </a-col>
        <a-col>
            <a-form-item label="Zavod" name="factory_id">
                <a-select v-model:value="form.factory_id"
                          :filter-option="filterOption"
                          show-search placeholder="Zavod...">
                    <a-select-option v-for="(factory, index) in factories"
                                     :key="`factory-id-${index}-${factory.id}`"
                                     :value="factory.id">{{ factory.name }} ({{ factory.number }})
                    </a-select-option>
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
import showValidationErrors from "@/utils/showValidationErrors";

export default {
    name: 'FactoryFloorCreate',
    props: {
        id: {
            type: Number
        },
        name: {
            type: String
        },
        number: {
            type: Number
        },
        factory: {
            type: Object
        }
    },
    data() {
        return {
            form: {
                name: null,
                number: null,
                factory_id: null
            },
            rules: {
                name: [{required: true, message: 'Sex nomini kiriting'}],
                number: [{required: true, message: 'Sex raqamini kiriting'}],
                factory_id: [{required: true, message: 'Zavodni tanlang'}],
            },
            factories: [],
        };
    },
    computed: {
        closeDrawer() {
            return this.$store.getters['drawer/getOpen'];
        }
    },
    methods: {
        init() {
            this.getFactories()
                .then(() => {
                    this.form = {
                        name: this.name,
                        number: this.number,
                        factory_id: this.factory.is_deleted ? null : this.factory.id
                    }
                })
                .then(() => this.$store.commit('spinner/toggleSpinning', 'drawer'))
        },
        async getFactories() {
            await this.$api.getFactories({list: 1}, ({data}) => this.factories = data.data);
        },
        onClose() {
            this.$store.dispatch('drawer/clearDrawer');
        },
        onFloorFinish(payload) {
            this.$store.commit('spinner/toggleSpinning', 'main');
            this.$api.updateFactoryFloor(
                this.id,
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
        onFloorFinishFailed(errors) {
            console.log(errors);
        },
        filterOption(input, option) {
            let regex = new RegExp(input.toLowerCase());
            return option.children()[0].children.toLowerCase().match(regex);
        }
    },
    watch: {
        closeDrawer(newValue) {
            if (!newValue) {
                this.form = {name: null, number: null, factory_id: null};
            }
        }
    }
}
</script>
