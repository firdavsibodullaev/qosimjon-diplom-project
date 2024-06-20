<script lang="ts">
import { defineComponent } from 'vue';
import formatDate from '@/utils/formatDate';

export default defineComponent({
	name: 'FactoryDeviceList',
	data() {
		return {};
	},
	props: {
		id: {
			required: true,
			type: Number,
		},
		device: {
			required: true,
			type: Object,
		},
		factory: {
			required: true,
			type: Object,
		},
		factory_floor: {
			required: true,
			type: Object,
		},
		number: {
			required: true,
			type: Number,
		},
		full_number: {
			required: true,
			type: String,
		},
		position: {
			required: true,
			type: String,
		},
		status: {
			required: true,
			type: String,
		},
		last_checked_at: {
			type: String,
		},
	},
	methods: {
		formatDate,
		init() {
			console.log(this.device);
			this.$store.commit('spinner/toggleSpinning', 'drawer');
		},
	},
});
</script>

<template>
	<div class="mt-4" v-if="device">
		<div class="grid grid-cols-2 gap-6 mb-4">
			<div class="text-lg"><strong>Nomi:</strong></div>
			<div class="text-lg">{{ device.name }}</div>
		</div>
		<div class="grid grid-cols-2 gap-6 mb-4">
			<div class="text-lg"><strong>Hususiyatlari:</strong></div>
			<div class="text-lg">
				<div
					class="my-2"
					v-for="(attribute, index) in device.attributes"
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
			<div class="text-lg">{{ full_number }}</div>
		</div>
		<div class="grid grid-cols-2 gap-6 mb-4">
			<div class="text-lg"><strong>Zavod:</strong></div>
			<div class="text-lg">{{ factory.name }} ({{ factory.number }})</div>
		</div>
		<div class="grid grid-cols-2 gap-6 mb-4">
			<div class="text-lg"><strong>Sex:</strong></div>
			<div class="text-lg">
				{{ factory_floor?.name }} ({{ factory_floor?.number }})
			</div>
		</div>
		<div class="grid grid-cols-2 gap-6 mb-4">
			<div class="text-lg">
				<strong>Oxirgi marta tekshiruvdan o'tkazilgan vaqt:</strong>
			</div>
			<div class="text-lg">
				{{ formatDate(last_checked_at) || '—' }}
			</div>
		</div>
	</div>
	<div v-else class="min-h-screen"></div>
</template>
<style scoped></style>
