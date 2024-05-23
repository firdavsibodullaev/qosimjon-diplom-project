<template>
	<a-form
		:model="form"
		:rules="rules"
		autocomplete="off"
		@finish="onFinish"
		@finishFailed="onFinishFailed"
		layout="vertical"
	>
		<a-space class="site-input-group-wrapper w-full" direction="vertical">
			<a-form-item label="Arizachi korxona">
				<a-input disabled :value="userFactory.name" />
			</a-form-item>
			<a-form-item label="Arizachi">
				<a-input disabled :value="user.name" />
			</a-form-item>
			<a-form-item label="Pribor" name="device_id">
				<a-select
					v-model:value="form.device_id"
					placeholder="Priborni tanlang"
					show-arrow
					class="w-full"
				>
					<a-select-option
						v-for="device in devices"
						:key="`factory-device-${device.id}`"
						:value="device.id"
					>
						{{ device.device.name }} ({{ device.full_number }})
					</a-select-option>
				</a-select>
			</a-form-item>
			<a-form-item label="Tekshiruvchi korxona" name="factory_id">
				<a-select
					v-model:value="form.factory_id"
					placeholder="Tekshiruvchi korxonani tanlang"
					show-arrow
					class="w-full"
				>
					<a-select-option
						v-for="factory in factories"
						:key="`factory-${factory.id}`"
						:value="factory.id"
					>
						{{ factory.name }}
					</a-select-option>
				</a-select>
			</a-form-item>
			<a-form-item
				name="document"
				label="Hujjat"
				extra="PDF hujjat yuklang, 10MBdan ortiq bo'lmasin"
			>
				<upload-file
					ref="fileUpload"
					id="document"
					:required="true"
					accept="application/pdf"
					v-model:value="form.document"
				>
					<template #icon><UploadOutlined class="mr-2" /></template>
					Hujjatni yuklang
				</upload-file>
			</a-form-item>
			<a-form-item>
				<a-button
					type="primary"
					class="bg-ant-primary"
					html-type="submit"
					>Saqlash
				</a-button>
			</a-form-item>
		</a-space>
	</a-form>
</template>
<script setup lang="ts">
import { useFactoryDevices } from '@/hooks/useFactoryDevices';
import { useFactories } from '@/hooks/useFactories';
import store from '@/store';
import { computed, ref, watch } from 'vue';
import type { IFactory } from '@/contracts/factory/IFactory';
import { useCalibrationCreate } from '@/hooks/calibration/useCalibrationCreate';
import type { IUser } from '@/contracts/user/IUser';
import { UploadOutlined } from '@ant-design/icons-vue';
import UploadFile from '@/components/UploadFile.vue';

const { devices, getDevices } = useFactoryDevices(false);
const { factories, getFactories } = useFactories(false);
const { create } = useCalibrationCreate();

const form = ref({
	device_id: null,
	factory_id: null,
	document: null,
});

const fileUpload = ref();

const userFactory = computed<IFactory>(
	() => store.getters['auth/getUser'].floors[0]?.factory,
);

const user = computed<IUser>(() => store.getters['auth/getUser']);

const rules = ref({
	device_id: { required: true, message: 'Priborni tanlang' },
	factory_id: { required: true, message: 'Tekshiruvchi korxonani tanlang' },
	document: { required: true, message: 'Hujjatni tanlang' },
});
const onFinish = (payload) => {
	store.commit('spinner/toggleSpinning', 'main');
	create(payload);
};
const onFinishFailed = () => {};

const init = async () => {
	await getDevices({ list: 1 });
	await getFactories({ type: 'tester', list: 1 });
	store.commit('spinner/toggleSpinning', 'drawer');
};

const closeDrawer = computed<boolean>(() => store.getters['drawer/getOpen']);

watch(closeDrawer, () => {
	fileUpload.value.removeSelectedFile();

	form.value = {
		device_id: null,
		factory_id: null,
		document: null,
	};
});

defineExpose({ init });
</script>
<style scoped></style>
