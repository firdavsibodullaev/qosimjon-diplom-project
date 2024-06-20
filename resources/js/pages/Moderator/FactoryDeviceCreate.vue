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
			<a-col class="w-full">
				<a-form-item label="Pribor" name="device_id">
					<a-select
						show-search
						v-model:value="form.device_id"
						placeholder="Priborni tanlang"
						show-arrow
						:filter-option="
							(input: string, option: any) =>
								filterDevices(input, option)
						"
						class="w-full"
					>
						<a-select-option
							v-for="device in devices"
							:key="`device-${device.id}-${device.name}`"
							:value="device.id"
							>{{ device.name }}
						</a-select-option>
					</a-select>
				</a-form-item>
			</a-col>
			<a-row :gutter="8">
				<a-col class="w-1/2">
					<a-form-item label="Zavod" name="factory_id">
						<a-select
							v-model:value="form.factory_id"
							placeholder="Zavodni tanlang"
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
				</a-col>
				<a-col class="w-1/2">
					<a-form-item label="Sex" name="factory_floor_id">
						<a-select
							v-model:value="form.factory_floor_id"
							placeholder="Sexni tanlang"
							show-arrow
							class="w-full"
						>
							<a-select-option
								v-for="floor in user.floors || []"
								:key="`floor-${floor.id}`"
								:value="floor.id"
							>
								{{ floor.name }}
							</a-select-option>
						</a-select>
					</a-form-item>
				</a-col>
			</a-row>
			<a-row :gutter="8">
				<a-col class="w-1/2">
					<a-form-item label="Pribor raqami" name="number">
						<a-input
							:prefix="numberPrefix"
							type="number"
							class="device-number"
							v-model:value.number="form.number"
							placeholder="Pribor raqamini kiriting"
						/>
					</a-form-item>
				</a-col>
				<a-col class="w-1/2">
					<a-form-item
						label="Har nechi oyda tekshiruvdan o'tishi kerak"
						name="check_every_time"
					>
						<a-input-number
							placeholder="Har nechi oyda tekshiruvdan o'tishi kerak ekanligini kiriting"
							v-model:value="form.check_every_time"
							class="w-full"
						/>
					</a-form-item>
				</a-col>
			</a-row>
			<a-row :gutter="8">
				<a-col class="w-1/2">
					<a-form-item label="Joyi" name="position">
						<a-select
							v-model:value="form.position"
							placeholder="Joyini tanlang"
							show-arrow
							class="w-full"
						>
							<a-select-option
								v-for="(text, pos) in list"
								:key="`position-${pos}`"
								:value="position[pos]"
								>{{ text }}
							</a-select-option>
						</a-select>
					</a-form-item>
				</a-col>
				<a-col class="w-1/2">
					<a-form-item label="Holati" name="status">
						<a-select
							v-model:value="form.status"
							placeholder="Holatini tanlang"
							show-arrow
							class="w-full"
						>
							<a-select-option
								v-for="(text, statusKey) in statusList"
								:key="`status-${statusKey}`"
								:value="status[statusKey]"
								>{{ text }}
							</a-select-option>
						</a-select>
					</a-form-item>
				</a-col>
			</a-row>
			<a-col>
				<a-form-item>
					<a-button
						type="primary"
						class="bg-ant-primary"
						html-type="submit"
						>Saqlash
					</a-button>
				</a-form-item>
			</a-col>
		</a-space>
	</a-form>
</template>
<script setup lang="ts">
import store from '@/store';
import { computed, ref } from 'vue';
import { useDevices } from '@/hooks/useDevices';
import type { IUser } from '@/contracts/user/IUser';
import type { IFactory } from '@/contracts/factory/IFactory';
import type { IFloor } from '@/contracts/floor/IFloor';
import { list, position } from '@/enums/factory-device/position';
import { list as statusList, status } from '@/enums/factory-device/status';
import type { IUploadPayload } from '@/contracts/factory-device/IUploadPayload';
import { useFactoryDevices } from '@/hooks/useFactoryDevices';

const { devices, getDevices } = useDevices();
const { createDevice } = useFactoryDevices(false);

const user = computed<IUser>(() => store.getters['auth/getUser']);
const factories = computed<IFactory[]>(
	() =>
		store.getters['auth/getUser'].floors
			?.map((floor: IFloor) => floor.factory)
			.unique('id') || [],
);
const numberPrefix = computed<string>(() => {
	const factory = factories.value.find(
		({ id }) => id == form.value.factory_id,
	);

	const floor = user.value.floors?.find(
		({ id }) => id == form.value.factory_floor_id,
	);

	return `${factory?.number || ''}${floor?.number || ''}`;
});

const form = ref<IUploadPayload>({
	device_id: null,
	factory_id: null,
	factory_floor_id: null,
	number: null,
	position: null,
	status: null,
	check_every_time: null,
});

const rules = ref({
	device_id: [{ required: true, message: 'Priborni tanlang' }],
	factory_id: [{ required: true, message: 'Zavodni tanlang' }],
	number: [{ required: true, message: 'Pribor raqamini kiriting' }],
	position: [{ required: true, message: 'Joyini tanlang' }],
	status: [{ required: true, message: 'Holatini tanlang' }],
	check_every_time: [
		{
			required: true,
			message:
				"Har nechi oyda tekshiruvdan o'tishi kerak ekanligini kiriting",
		},
	],
	factory_floor_id: [
		{
			required: user.value.has_permission_to_all_floors,
			message: 'Sexni tanlang',
		},
	],
});

const filterDevices = (searchName: string, option: { key: string }) => {
	const regex = new RegExp(
		`^device\-[0-9]+\-${searchName.toLowerCase()}+`,
		'g',
	);

	return option.key.toLowerCase().match(regex);
};

const onFinish = (payload: IUploadPayload) => {
	createDevice(payload);
	store.commit('spinner/toggleSpinning', 'main');
	store.commit('factory/setIsReload', true);
	store.dispatch('drawer/clearDrawer');
};

const onFinishFailed = () => {};

const init = () => {
	getDevices({ paginate: '0' }).then(() =>
		store.commit('spinner/toggleSpinning', 'drawer'),
	);
};
defineExpose({ init });
</script>
<style scoped>
.device-number:deep(input[type='number']::-webkit-outer-spin-button),
.device-number:deep(input[type='number']::-webkit-inner-spin-button) {
	-webkit-appearance: none;
	margin: 0;
}

.device-number:deep(input[type='number']) {
	-moz-appearance: textfield;
	appearance: textfield;
}
</style>
