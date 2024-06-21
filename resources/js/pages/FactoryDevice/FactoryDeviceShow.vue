<script lang="ts">
import { defineComponent, type PropType } from 'vue';
import formatDate from '@/utils/formatDate';
import DocumentPreview from '@/components/DocumentPreview.vue';
import FactoryDeviceCalibrationDetail from '@/pages/FactoryDevice/FactoryDeviceCalibrationDetail.vue';
import type { ICalibrationType } from '@/contracts/application/ICalibrationType';
import { translation as statusTranslationEnum } from '@/enums/factory-device/status';
import { translation as positionTranslationEnum } from '@/enums/factory-device/position';

export default defineComponent({
	name: 'FactoryDeviceList',
	computed: {
		statusTranslationEnum() {
			return statusTranslationEnum;
		},
		positionTranslationEnum() {
			return positionTranslationEnum;
		},
		nextCheckDate() {
			if (!this.last_checked_at) {
				return '';
			}

			const date = new Date(this.last_checked_at);

			date.setMonth(date.getMonth() + this.check_every_time);
			return date;
		},
	},
	components: { FactoryDeviceCalibrationDetail, DocumentPreview },
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
		check_every_time: {
			type: Number,
			default: null,
		},
		last_checked_at: {
			type: String,
			default: null,
		},
		calibrations: {
			type: Array as PropType<ICalibrationType[]>,
			required: true,
		},
	},
	methods: {
		formatDate,
		init() {
			this.$store.commit('spinner/toggleSpinning', 'drawer');
		},
	},
});
let date = new Date();
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
			<div class="text-lg"><strong>Joylashgan joyi:</strong></div>
			<div class="text-lg">
				{{ positionTranslationEnum[position] }}
			</div>
		</div>
		<div class="grid grid-cols-2 gap-6 mb-4">
			<div class="text-lg"><strong>Holati:</strong></div>
			<div class="text-lg">
				{{ statusTranslationEnum[status] }}
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
		<div class="grid grid-cols-2 gap-6 mb-4">
			<div class="text-lg">
				<strong>Har necha oyda tekshiruvdan o'tkaziladi:</strong>
			</div>
			<div class="text-lg">
				<div v-if="check_every_time">
					{{ check_every_time }} oy <br />
					<span class="text-sm italic">
						Keyingisi: {{ formatDate(nextCheckDate, false) }}
					</span>
				</div>
			</div>
		</div>
		<div class="grid grid-cols-2 gap-6 mb-4">
			<div class="text-lg">
				<strong>Oxirgi tekshiruv natijasi:</strong>
			</div>
			<div class="text-lg">
				<div v-if="calibrations.length">
					<b>{{ calibrations[0].status.value }}</b>
					<span
						:class="`${calibrations[0].result?.key === 'approved' && 'text-green-700'} ${calibrations[0].result?.key === 'rejected' && 'text-red-700'}`"
					>
						({{ calibrations[0].result?.value }})
					</span>
				</div>
				<div v-else>—</div>
			</div>
		</div>
		<a-collapse v-if="calibrations.length" ghost>
			<a-collapse-panel
				key="1"
				class="text-lg font-bold"
				header="Barcha qiyoslashlar"
			>
				<div class="font-normal">
					<div
						v-for="(calibration, index) in calibrations"
						:key="`calibration-information-${index}`"
						class="mb-4 border-2 shadow p-2 rounded"
					>
						<FactoryDeviceCalibrationDetail
							:calibration="calibration"
						/>
					</div>
				</div>
			</a-collapse-panel>
		</a-collapse>
	</div>
	<div v-else class="min-h-screen"></div>
</template>
<style scoped>
div:deep(.ant-collapse-header) {
	align-items: center !important;
}
</style>
