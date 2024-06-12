<template>
	<div>
		<Accept
			v-if="status.key === 'new'"
			@updateStatus="updateStatus"
			@updateChecker="updateChecker"
			:id="id"
		/>
		<div v-if="status.key === 'process'">
			<a-col>
				<a-checkbox
					class="text-xl"
					value="approve"
					:checked="checkboxValue === 'approve'"
					@change="
						() =>
							(checkboxValue =
								checkboxValue != 'approve' ? 'approve' : null)
					"
					>Tasdiqlash
				</a-checkbox>
			</a-col>
			<a-col>
				<a-checkbox
					class="text-xl"
					value="reject"
					:checked="checkboxValue === 'reject'"
					@change="
						() =>
							(checkboxValue =
								checkboxValue != 'reject' ? 'reject' : null)
					"
					>Rad etish
				</a-checkbox>
			</a-col>
			<div v-if="checkboxValue === 'approve'">
				<p class="text-xl mt-14">Tasdiqlash</p>
				<hr class="mt-2 mb-10" />
				<Approve
					@approve="
						(approveForm) =>
							handleApplicationReact('approve', approveForm)
					"
					:id="id"
				/>
			</div>
			<div v-if="checkboxValue === 'reject'">
				<p class="text-xl mt-14">Rad etish</p>
				<hr class="mt-2 mb-10" />
				<Reject
					@reject="
						(rejectForm) =>
							handleApplicationReact('reject', rejectForm)
					"
					:id="id"
				/>
			</div>
		</div>
	</div>
</template>

<script setup lang="ts">
import Accept from '@/pages/Tester/Accept.vue';
import Approve from '@/pages/Tester/Approve.vue';
import { ref } from 'vue';
import Reject from '@/pages/Tester/Reject.vue';
import api from '@/libs/api';

const emits = defineEmits([
	'updateStatus',
	'updateChecker',
	'updateResult',
	'updateComment',
	'updateReactDocumentValue',
]);
const props = defineProps<{
	id: number;
	status: { key: string; value: string };
}>();

const checkboxValue = ref();

const init = () => {};

const updateStatus = (value: any) => emits('updateStatus', value);

const updateChecker = (value: any) => emits('updateChecker', value);

const handleApplicationReact = (action: string, actionForm: any) => {
	if (action === 'approve') {
		api.apis.approveApplication(props.id, actionForm, onSuccess);
	} else if (action === 'reject') {
		api.apis.rejectApplication(props.id, actionForm, onSuccess);
	}
};

const onSuccess = ({ data }) => {
	const { status, result, comment, react_document } = data.data;
	updateStatus(status);
	emits('updateResult', result);
	emits('updateComment', comment);
	emits('updateReactDocumentValue', react_document);
};

defineExpose({ init });
</script>
<style scoped></style>
