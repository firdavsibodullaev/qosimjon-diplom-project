<template>
	<div>
		<a-row>
			<a-col :span="resultValue?.key == null ? 12 : 24">
				<div
					class="overflow-y-auto max-h-[calc(100vh-81px)]"
					style="scrollbar-gutter: stable"
				>
					<a-col :span="24">
						<p class="text-xl mb-10 border-b-2">
							<strong>Batafsil ma'lumot</strong>
						</p>
					</a-col>
					<CalibrationShow
						:id="id"
						:applicant-factory="applicantFactory"
						:checked_at="checked_at"
						:document="document"
						:created_at="created_at"
						:checker="checkerValue"
						:applicant="applicant"
						:result="resultValue"
						:checker-factory="checkerFactory"
						:comment="commentValue"
						:deadline="deadline"
						:device="device"
						:status="statusValue"
						:react_document="reactDocumentValue"
					/>
				</div>
			</a-col>
			<a-col v-if="resultValue?.key == null" :span="12">
				<div
					class="overflow-y-auto max-h-[calc(100vh-81px)] ml-2"
					style="scrollbar-gutter: stable"
				>
					<a-col :span="24">
						<p class="text-xl mb-10 border-b-2">
							<strong>Natija berish</strong>
						</p>
					</a-col>
					<React
						:id="id"
						:status="statusValue"
						@updateStatus="updateStatus"
						@updateChecker="updateChecker"
						@updateResult="updateResult"
						@updateComment="updateComment"
						@updateReactDocumentValue="updateReactDocumentValue"
					/>
				</div>
			</a-col>
		</a-row>
	</div>
</template>

<script setup lang="ts">
import store from '@/store';
import type { IUser } from '@/contracts/user/IUser';
import type { IFactory } from '@/contracts/factory/IFactory';
import type { IFactoryDevice } from '@/contracts/factory-device/IFactoryDevice';
import { computed, ref } from 'vue';
import CalibrationShow from '@/pages/Worker/CalibrationShow.vue';
import React from '@/pages/Tester/React.vue';

const props = defineProps<{
	applicant: IUser;
	applicantFactory: IFactory;
	checker: IUser | null;
	checkerFactory: IFactory;
	comment: string | null;
	created_at: string;
	deadline: string;
	checked_at: string | null;
	device: IFactoryDevice;
	document: { url: string; name: string; size: string };
	id: number;
	result: { key: string; value: string };
	status: { key: string; value: string };
	react_document: { url: string; name: string; size: string } | null;
}>();

const statusValue = ref(props.status);
const checkerValue = ref(props.checker);
const resultValue = ref(props.result);
const commentValue = ref(props.comment);
const reactDocumentValue = ref(props.react_document);
const closeDrawer = computed(() => store.getters['drawer/getOpen']);

const init = () => {
	store.commit('spinner/toggleSpinning', 'drawer');
};

const updateStatus = (value: { key: string; value: string }) => {
	statusValue.value = value;
};

const updateChecker = (value: any) => {
	checkerValue.value = value;
};

const updateResult = (value: any) => {
	resultValue.value = value;
};

const updateComment = (value: any) => {
	commentValue.value = value;
};

const updateReactDocumentValue = (value: any) => {
	reactDocumentValue.value = value;
};

defineExpose({ init });
</script>
<style scoped></style>
