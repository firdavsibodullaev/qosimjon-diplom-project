<script setup lang="ts">
import DocumentPreview from '@/components/DocumentPreview.vue';
import formatDate from '../../utils/formatDate';
import type { ICalibrationType } from '@/contracts/application/ICalibrationType';

defineProps<{
	calibration: ICalibrationType;
}>();
</script>

<template>
	<div>
		<div class="grid grid-cols-2 gap-6 mb-2">
			<div class="text-lg">
				<strong>Ariza berilgan vaqt:</strong>
			</div>
			<div class="text-lg">
				{{ formatDate(calibration.created_at, false) }}
			</div>
		</div>
		<div class="grid grid-cols-2 gap-6 mb-2">
			<div class="text-lg">
				<strong>Ariza beruvchi xodim:</strong>
			</div>
			<div class="text-lg">
				{{ calibration.applicant.name }}
			</div>
		</div>

		<div class="grid grid-cols-2 gap-6 mb-2">
			<div class="text-lg">
				<strong>Ariza berish hujjati:</strong>
			</div>
			<div class="text-lg">
				<DocumentPreview :document="calibration.document" />
			</div>
		</div>
		<div class="grid grid-cols-2 gap-6 mb-2">
			<div class="text-lg">
				<strong>Ko'rib chiquvchi korxona:</strong>
			</div>
			<div class="text-lg">
				{{ calibration.checkerFactory.name }}
			</div>
		</div>
		<div class="grid grid-cols-2 gap-6 mb-2">
			<div class="text-lg">
				<strong>Ko'rib chiquvchi xodim:</strong>
			</div>
			<div class="text-lg">
				{{ calibration.checker?.name }}
			</div>
		</div>
		<div class="grid grid-cols-2 gap-6 mb-2">
			<div class="text-lg">
				<strong>Holati:</strong>
			</div>
			<div class="text-lg">
				{{ calibration.status.value }}
			</div>
		</div>
		<div class="grid grid-cols-2 gap-6 mb-2">
			<div class="text-lg">
				<strong>Natija:</strong>
			</div>
			<div
				:class="`text-lg ${calibration.result?.key === 'approved' && 'text-green-500'} ${calibration.result?.key === 'rejected' && 'text-red-500'}`"
			>
				{{ calibration.result?.value }}
			</div>
		</div>
		<div class="grid grid-cols-2 gap-6 mb-2">
			<div class="text-lg">
				<strong>Natija hujjati:</strong>
			</div>
			<div class="text-lg">
				<DocumentPreview :document="calibration.react_document" />
			</div>
		</div>
		<div class="grid grid-cols-2 gap-6 mb-2">
			<div class="text-lg">
				<strong>Ko'rib chiqilgan vaqt:</strong>
			</div>
			<div class="text-lg">
				{{ formatDate(calibration.checked_at, false) }}
			</div>
		</div>
	</div>
</template>
<style scoped></style>
