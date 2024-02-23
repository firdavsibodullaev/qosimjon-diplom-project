<template>
    <a-form :model="form"
            :rules="rules"
            autocomplete="off"
            @finish="onFinish"
            @finishFailed="onFinishFailed"
            layout="vertical">
        <a-space class="site-input-group-wrapper w-full" direction="vertical">
            <a-form-item label="Nomi" name="name">
                <a-input v-model:value="form.name" placeholder="Nomi..."/>
            </a-form-item>
            <a-form-item>
                <a-button type="dashed"
                          class="flex items-center"
                          @click="addAttribute"
                          html-type="button">
                    <PlusOutlined/>
                    Hususiyat qo'shish
                </a-button>
            </a-form-item>
            <div class="max-h-[540px] flex items-center flex-col p-4 overflow-y-auto border-2 rounded">
                <div v-for="(attribute, index) in form.attributes"
                     :key="`space-${index}`"
                     :class="{'border-b-2 pb-6': index !== form.attributes.length - 1}"
                     class="flex my-[8px] items-center justify-between w-full gap-7">
                    <div class="flex w-full justify-between gap-4 flex-wrap">
                        <a-form-item class="w-[72%] mb-0" label="Hususiyat" :name="['attributes',index, 'attribute']">
                            <a-select v-model:value="form.attributes[index].attribute"
                                      @change="setAttributeValue"
                                      :filter-option="filterOption"
                                      :options="attributeOptions(index)"
                                      :attributeKey="index"
                                      show-search
                                      placeholder="Hususiyat...">
                                <template #dropdownRender="{ menuNode: menu }">
                                    <v-nodes :key="`attribute-options-${index}`" :vnodes="menu"/>
                                    <a-divider style="margin: 4px 0"/>
                                    <a-space style="padding: 4px 8px">
                                        <a-input v-model:value="newData"
                                                 placeholder="Hususiyatni kiriting"/>
                                        <a-button type="text" @click="addItem">
                                            <template #icon>
                                                <plus-outlined/>
                                            </template>
                                            Yangi hususiyat
                                        </a-button>
                                    </a-space>
                                </template>
                            </a-select>
                        </a-form-item>
                        <a-form-item class="w-1/4 mb-0"
                                     label="O'lchov birlgi"
                                     :name="['attributes',index, 'measurement_unit']">
                            <a-input v-model:value="form.attributes[index].measurement_unit"
                                     placeholder="O'lchov birligini kiriting"/>
                        </a-form-item>
                        <a-form-item class="w-full mb-0" label="Qiymati" :name="['attributes',index,'value']">
                            <a-select v-model:value="form.attributes[index].value"
                                      :options="values[index] ?? []"
                                      @change="setValueValue"
                                      placeholder="Qiymati...">
                                <template #dropdownRender="{ menuNode: menu }">
                                    <v-nodes :key="`value-options-${index}`" :vnodes="menu"/>
                                    <a-divider style="margin: 4px 0"/>
                                    <a-space style="padding: 4px 8px">
                                        <a-input
                                            v-model:value="newData"
                                            placeholder="Qiymatni kiriting"/>
                                        <a-button type="text"
                                                  :valueKey="index"
                                                  @click="addValueItem">
                                            <template #icon>
                                                <plus-outlined/>
                                            </template>
                                            Yangi qiymat
                                        </a-button>
                                    </a-space>
                                </template>
                            </a-select>
                        </a-form-item>
                    </div>
                    <MinusCircleOutlined @click="removeAttribute(index)"
                                         class="mt-7 hover:text-red-600 transition duration-100"/>
                </div>
            </div>
            <a-form-item>
                <a-button type="primary" class="bg-ant-primary" html-type="submit">Saqlash</a-button>
            </a-form-item>
        </a-space>
    </a-form>
</template>
<script>

import showValidationErrors from "@/utils/showValidationErrors";
import toastr from "toastr";

export default {
    name: 'DeviceCreate',
    components: {
        VNodes: {
            props: {
                vnodes: {
                    type: Object,
                    required: true,
                },
            },
            render() {
                return this.vnodes;
            },
        }
    },
    data() {
        return {
            form: {
                name: null,
                attributes: [{attribute: null, value: null, measurement_unit: null}]
            },
            rules: {
                name: [{required: true, message: 'Nomini kiriting'}]
            },
            attributes: [],
            values: [],
            newData: null
        };
    },
    computed: {
        closeDrawer() {
            return this.$store.getters['drawer/getOpen'];
        }
    },
    methods: {
        init() {
            this.getAttributes()
                .then(() => this.values = [])
                .then(() => this.$store.commit('spinner/toggleSpinning', 'drawer'));
        },
        async getAttributes() {
            await this.$api.getAttributes(
                {sorter: 'name'},
                ({data: res}) => {
                    this.attributes = res.data;
                },
                (error) => {
                    console.log(error);
                }
            );
        },
        addAttribute() {
            this.form.attributes.push({attribute: null, value: null});
        },
        attributeOptions(key) {
            return this.attributes.map((item, index) => {
                return {
                    value: item.id,
                    label: item.name,
                    key: `attribute-${index}-${item.id}`,
                    attributeKey: key,
                }
            });
        },
        filterOption(input, option) {
            let regex = new RegExp(input.toLowerCase());

            return option.label.toLowerCase().match(regex);
        },
        setAttributeValue(value, option) {
            let attributes = this.attributes.filter((attribute) => attribute.id === value);
            let index = option.attributeKey;

            if (attributes.length === 0) {
                return;
            }

            let attribute = attributes[0];

            let values = attribute.values.map((valueData) => {
                return {
                    value: valueData.id,
                    label: valueData.value,
                    key: `value-${index}-${valueData.id}`,
                };
            });

            if (this.values[index]) {
                this.values[index] = values;
            } else {
                this.values.push(values);
            }

            this.form.attributes[index].value = null;
            this.form.attributes[index].attribute = value;
            this.form.attributes[index].measurement_unit = attribute.measurement_unit ?? null;
        },
        setValueValue(value, option) {
            let index = option.key.split('-')[1];

            this.form.attributes[index].value = value;
        },
        addItem(e) {
            e.preventDefault();

            this.attributes.unshift({
                id: this.newData,
                name: this.newData,
                values: []

            });
            this.newData = '';

        },
        addValueItem(e) {
            e.preventDefault();
            let key = e.target.closest('button').getAttribute('valueKey');

            this.values[key].unshift({
                value: this.newData,
                label: this.newData,
                key: `value-${key}-${this.newData}`
            });

            this.newData = '';

        },
        removeAttribute(index) {
            if (this.form.attributes.length === 1) return;
            this.form.attributes.splice(index, 1);
            this.values.splice(index, 1);
        },
        onClose() {
            this.$store.dispatch('drawer/clearDrawer');
        },
        onFinish(payload) {
            this.$store.commit('spinner/toggleSpinning', 'main');
            this.$api.createDevice(
                payload,
                ({data}) => {
                    toastr.success(data.message);
                    this.$store.commit('spinner/toggleSpinning', 'main');
                    this.$store.commit('factory/setIsReload', true);
                    this.onClose();
                },
                (response) => {
                    console.log(response);
                    this.$store.commit('spinner/toggleSpinning', 'main');
                    if (response.status === 422) {
                        showValidationErrors(response.data.errors);
                    }
                });
        },
        onFinishFailed(errors) {
            console.log(errors);
        }
    },
    watch: {
        closeDrawer(newValue) {
            if (!newValue) {
                this.form = {
                    name: null,
                    attributes: [{attribute: null, value: null, measurement_unit: null}]
                };
            }
        }
    }
}
</script>
