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
					accept="application/pdf"
					v-model:value="form.document"
					:default-file="defaultDocument"
				>
					<template #icon>
						<UploadOutlined class="mr-2" />
					</template>
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
import type { IUser } from '@/contracts/user/IUser';
import type { IFactory } from '@/contracts/factory/IFactory';
import type { IFactoryDevice } from '@/contracts/factory-device/IFactoryDevice';
import UploadFile from '@/components/UploadFile.vue';
import { UploadOutlined } from '@ant-design/icons-vue';
import { computed, ref, watch } from 'vue';
import store from '@/store';
import { useFactoryDevices } from '@/hooks/useFactoryDevices';
import { useFactories } from '@/hooks/useFactories';
import { useCalibrationCreate } from '@/hooks/calibration/useCalibrationCreate';

const { devices, getDevices } = useFactoryDevices(false);
const { factories, getFactories } = useFactories(false);
const { edit } = useCalibrationCreate();

const props = defineProps<{
	applicant: IUser;
	applicantFactory: IFactory;
	checker: IUser | null;
	checkerFactory: IFactory;
	comment: string | null;
	created_at: string;
	device: IFactoryDevice;
	document?: { url: string; name: string; size: string };
	id: number;
	result: { key: string; value: string };
	status: { key: string; value: string };
}>();

const form = ref<{
	device_id: null | number;
	factory_id: null | number;
	document: null | object;
}>({
	device_id: null,
	factory_id: null,
	document: null,
});

const fileUpload = ref();

const defaultDocument = ref<
	{ url: string; name: string; size: string } | undefined
>(props.document);

const userFactory = computed<IFactory>(
	() => store.getters['auth/getUser'].floors[0]?.factory,
);

const user = computed<IUser>(() => store.getters['auth/getUser']);

const closeDrawer = computed<boolean>(() => store.getters['drawer/getOpen']);

const rules = ref({
	device_id: { required: true, message: 'Priborni tanlang' },
	factory_id: { required: true, message: 'Tekshiruvchi korxonani tanlang' },
});

const onFinish = (payload) => {
	store.commit('spinner/toggleSpinning', 'main');
	edit(props.id, payload);
};

const onFinishFailed = () => {};

const init = async () => {
	form.value = {
		device_id: props.device.id,
		factory_id: props.checkerFactory.id,
		document: null,
	};
	defaultDocument.value = props.document;

	await getDevices({ list: 1 });
	await getFactories({ type: 'tester', list: 1 });
	store.commit('spinner/toggleSpinning', 'drawer');
};

watch(closeDrawer, () => {
	form.value = {
		device_id: null,
		factory_id: null,
		document: null,
	};

	defaultDocument.value = undefined;
	fileUpload.value.removeSelectedFile();
});

defineExpose({ init });
</script>
<style scoped></style>
