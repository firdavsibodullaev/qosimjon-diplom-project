<template>
	<a-popconfirm
		:disabled="isDisabled"
		title="Qabul qilasizmi?"
		@confirm="accept(id)"
		cancel-text="Yo'q"
		ok-text="Ha"
	>
		<a-button block type="primary">Qabul qilish</a-button>
	</a-popconfirm>
</template>

<script setup lang="ts">
import api from '@/libs/api';
import { ref } from 'vue';

const props = defineProps<{
	id: number;
}>();
const isDisabled = ref(false);
const emits = defineEmits(['updateStatus', 'updateChecker']);

const accept = (id: number) => {
	isDisabled.value = true;

	api.apis
		.acceptApplication(
			id,
			({ data }) => {
				const { status, checker } = data.data;

				emits('updateStatus', status);
				emits('updateChecker', checker);
			},
			(response) => {},
		)
		.finally(() => (isDisabled.value = false));
};
</script>
<style scoped></style>
