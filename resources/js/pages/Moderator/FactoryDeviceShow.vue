<template>
	<div class="mt-4" v-if="device.device">
		<div class="grid grid-cols-2 gap-6 mb-4">
			<div class="text-lg"><strong>Nomi:</strong></div>
			<div class="text-lg">{{ device.device.name }}</div>
		</div>
		<div class="grid grid-cols-2 gap-6 mb-4">
			<div class="text-lg"><strong>Hususiyatlari:</strong></div>
			<div class="text-lg">
				<div
					class="my-2"
					v-for="(attribute, index) in device.device.attributes"
					:key="`attribute-${index}`"
				>
					<strong>{{ attribute.value.attribute.name }}:</strong>
					{{ attribute.value.value }} ({{
						attribute.measurement_unit
					}})
				</div>
			</div>
		</div>
		<div class="grid grid-cols-2 gap-6 mb-4">
			<div class="text-lg"><strong>Raqami:</strong></div>
			<div class="text-lg">{{ device.full_number }}</div>
		</div>
		<div class="grid grid-cols-2 gap-6 mb-4">
			<div class="text-lg">
				<strong
					>Qurilma har necha oyda tekshiruvdan o'tishi kerak:</strong
				>
			</div>
			<div class="text-lg">{{ device.check_every_time }}</div>
		</div>
		<div class="grid grid-cols-2 gap-6 mb-4">
			<div class="text-lg"><strong>Zavod:</strong></div>
			<div class="text-lg">
				{{ device.factory.name }} ({{ device.factory.number }})
			</div>
		</div>
		<div class="grid grid-cols-2 gap-6 mb-4">
			<div class="text-lg"><strong>Sex:</strong></div>
			<div class="text-lg">
				{{ device.factory_floor?.name }} ({{
					device.factory_floor?.number
				}})
			</div>
		</div>
	</div>
	<div v-else class="min-h-screen"></div>
</template>
<script setup lang="ts">
import type { IFactory } from '@/contracts/factory/IFactory';
import type { IFloor } from '@/contracts/floor/IFloor';
import { position } from '@/enums/factory-device/position';
import { status } from '@/enums/factory-device/status';
import type { IDevice } from '@/contracts/device/IDevice';
import store from '@/store';

const device = defineProps<{
	id: number;
	device: IDevice;
	factory: IFactory;
	factory_floor?: IFloor;
	number: number;
	full_number: number;
	position: position;
	status: status;
	check_every_time: number | null;
}>();

const init = () => {
	store.commit('spinner/toggleSpinning', 'drawer');
};
defineExpose({ init });
</script>
