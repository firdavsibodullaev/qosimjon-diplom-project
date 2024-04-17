<template>
	<a-form
		:model="form"
		:rules="rules"
		autocomplete="off"
		@finish="onFinish"
		@finishFailed="onFinishFailed"
		layout="vertical"
	>
		<a-col>
			<a-form-item label="Zavod nomi" name="name">
				<a-input
					v-model:value="form.name"
					placeholder="Zavod nomi..."
				/>
			</a-form-item>
		</a-col>
		<a-col>
			<a-form-item label="Zavod raqami" name="number">
				<a-input
					v-model:value="form.number"
					placeholder="Zavod raqami..."
				/>
			</a-form-item>
		</a-col>
		<a-col>
			<a-form-item label="Zavod turi" name="type">
				<a-select v-model:value="form.type" placeholder="Zavod turi...">
					<a-select-option
						v-for="(type, index) in types"
						:key="`factory-type-${index}`"
						:value="index"
						>{{ type }}
					</a-select-option>
				</a-select>
			</a-form-item>
		</a-col>
		<a-col>
			<a-form-item>
				<a-button
					type="primary"
					class="bg-ant-primary"
					html-type="submit"
					>Saqlash</a-button
				>
			</a-form-item>
		</a-col>
	</a-form>
</template>
<script>
import { success } from 'toastr';
import factoryType from '@/pages/Admin/Factory/factoryType';
import showValidationErrors from '@/utils/showValidationErrors';

export default {
	name: 'FactoryCreate',
	data() {
		return {
			form: {
				name: null,
				number: null,
				type: null,
			},
			rules: {
				name: [{ required: true, message: 'Zavod nomini kiriting' }],
				number: [
					{ required: true, message: 'Zavod raqamini kiriting' },
				],
				type: [{ required: true, message: 'Zavod turini kiriting' }],
			},
			types: factoryType,
		};
	},
	computed: {
		closeDrawer() {
			return this.$store.getters['drawer/getOpen'];
		},
	},
	methods: {
		init() {
			this.$store.commit('spinner/toggleSpinning', 'drawer');
		},
		onClose() {
			this.$store.dispatch('drawer/clearDrawer');
		},
		onFinish(payload) {
			this.$store.commit('spinner/toggleSpinning', 'main');
			this.$api.createFactory(
				payload,
				({ data }) => {
					success(data.message);
					this.$store.commit('spinner/toggleSpinning', 'main');
					this.$store.commit('factory/setIsReload', true);
					this.onClose();
				},
				({ response }) => {
					this.$store.commit('spinner/toggleSpinning', 'main');
					if (response.status === 422) {
						showValidationErrors(response.data.errors);
					}
				},
			);
		},
		onFinishFailed(errors) {
			console.log(errors);
		},
	},
	watch: {
		closeDrawer(newValue) {
			if (!newValue) {
				this.form = { name: null, number: null, type: null };
			}
		},
	},
};
</script>
