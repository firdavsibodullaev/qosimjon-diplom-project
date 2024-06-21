<template>
	<div>
		<a-row>
			<a-col :span="8" class="text-lg">
				<strong>Ariza IDsi</strong>
			</a-col>
			<a-col :span="16" class="text-lg">
				{{ id }}
			</a-col>
		</a-row>
		<hr class="my-4" />
		<a-row>
			<a-col :span="8" class="text-lg">
				<strong>Hujjat</strong>
			</a-col>
			<a-col :span="16" class="text-lg">
				<DocumentPreview :document="document" />
			</a-col>
		</a-row>
		<hr class="my-4" />
		<a-row>
			<a-col :span="8" class="text-lg">
				<strong>Ariza Holati</strong>
			</a-col>
			<a-col :span="16">
				<span
					:class="`text-lg rounded py-1 px-2 ${getStatusBlockBackgroundClass(status?.key)}`"
				>
					{{ status?.value }}
				</span>
			</a-col>
		</a-row>
		<hr class="my-4" />
		<a-row>
			<a-col :span="8" class="text-lg">
				<strong>Natija</strong>
			</a-col>
			<a-col :span="16">
				<span
					:class="`text-lg rounded py-1 px-2 ${getStatusBlockBackgroundClass(result?.key)}`"
				>
					{{ result?.value || "Ko'rib chiqilmoqda" }}
				</span>
			</a-col>
		</a-row>
		<a-row class="my-2">
			<a-col :span="8" class="text-lg">
				<strong>Hisobot</strong>
			</a-col>
			<a-col :span="16" class="text-lg">
				{{ comment }}
			</a-col>
		</a-row>
		<a-row>
			<a-col :span="8" class="text-lg">
				<strong>Natija hujjati</strong>
			</a-col>
			<a-col :span="16" class="text-lg">
				<DocumentPreview :document="react_document" />
			</a-col>
		</a-row>
		<hr class="my-4" />
		<a-row>
			<a-col :span="8" class="text-lg">
				<strong>Ariza berilgan vaqt</strong>
			</a-col>
			<a-col :span="16" class="text-lg">
				{{ formatDate(created_at) }}
			</a-col>
		</a-row>
		<hr class="my-4" />
		<a-row>
			<a-col :span="8" class="text-lg">
				<strong>Ko'rib chiqish muddati</strong>
			</a-col>
			<a-col :span="16" class="text-lg">
				{{ formatDate(deadline) }} <br />
				<span v-if="status?.key !== 'reviewed'" class="text-sm"
					>({{ timer }})</span
				>
			</a-col>
		</a-row>
		<hr class="my-4" />
		<a-row>
			<a-col :span="8" class="text-lg">
				<strong>Ko'rib chiqilgan vaqt</strong>
			</a-col>
			<a-col :span="16" class="text-lg">
				<span v-if="checked_at">{{ formatDate(checked_at) }}</span>
			</a-col>
		</a-row>
		<hr class="my-4" />
		<a-row>
			<a-col :span="8" class="text-lg">
				<strong>Pribor</strong>
			</a-col>
			<a-col :span="16" class="text-lg">
				<span
					>{{ device?.device.name }} ({{ device?.full_number }})</span
				>
			</a-col>
		</a-row>
		<a-row class="mt-4">
			<a-col :span="8" class="text-lg">
				<strong> Hususiyatlari </strong>
			</a-col>
			<a-col :span="16" class="text-lg">
				<div
					class="my-2"
					v-for="(attribute, index) in device?.device.attributes ||
					[]"
					:key="`attribute-${index}`"
				>
					<strong>{{ attribute.value.attribute.name }}:</strong>
					{{ attribute.value.value }} ({{
						attribute.measurement_unit
					}})
				</div>
			</a-col>
		</a-row>
		<hr class="my-4" />
		<a-row>
			<a-col :span="8" class="text-lg">
				<strong>Arizachi</strong>
			</a-col>
			<a-col :span="16" class="text-lg">
				<a-row>
					<a-col :span="8" class="text-lg mb-4">
						<strong>Korxona</strong>
					</a-col>
					<a-col :span="16" class="text-lg mb-4">
						{{ applicantFactory?.name }} ({{
							applicantFactory?.number
						}})
					</a-col>
					<a-col :span="8" class="text-lg">
						<strong>Xodim</strong>
					</a-col>
					<a-col :span="16" class="text-lg">
						{{ applicant?.name }}
					</a-col>
				</a-row>
			</a-col>
		</a-row>
		<hr class="my-4" />
		<a-row>
			<a-col :span="8" class="text-lg">
				<strong>Tekshiruvchi</strong>
			</a-col>
			<a-col :span="16" class="text-lg">
				<a-row>
					<a-col :span="8" class="text-lg mb-4">
						<strong>Korxona</strong>
					</a-col>
					<a-col :span="16" class="text-lg mb-4">
						{{ checkerFactory?.name }}
						<span v-if="checkerFactory?.number">
							({{ checkerFactory?.number }})
						</span>
					</a-col>
					<a-col :span="8" class="text-lg">
						<strong>Xodim</strong>
					</a-col>
					<a-col :span="16" class="text-lg">
						{{ checker?.name }}
					</a-col>
				</a-row>
			</a-col>
		</a-row>
		<hr class="my-4" />
	</div>
</template>
<script setup lang="ts">
import store from '@/store';
import type { IUser } from '@/contracts/user/IUser';
import type { IFactory } from '@/contracts/factory/IFactory';
import type { IFactoryDevice } from '@/contracts/factory-device/IFactoryDevice';
import formatDate from '@/utils/formatDate';
import { countDay } from '@/utils/countDay';
import { computed, ref, watch } from 'vue';
import DocumentPreview from '@/components/DocumentPreview.vue';

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

const timer = ref<string>('');

const interval = ref(countDay(props.deadline, timer));

const getStatusBlockBackgroundClass = (key: string) => {
	let className = '';

	switch (key) {
		case 'new':
			className = 'bg-blue-500 text-white';
			break;
		case 'process':
			className = 'bg-yellow-500';
			break;
		case 'reviewed':
		case 'approved':
			className = 'bg-green-500';
			break;
		case 'rejected':
			className = 'bg-red-500 text-white';
			break;
		case null:
			className = 'bg-gray-300';
			break;
	}

	return className;
};

const closeDrawer = computed(() => store.getters['drawer/getOpen']);
const statusValue = computed(() => props?.status);

watch(closeDrawer, (newValue) => {
	if (!newValue) {
		interval.value = countDay(props.deadline, timer, interval.value);
	} else {
		interval.value = countDay(props.deadline, timer);
	}
});

watch(statusValue, (newValue) => {
	if (newValue?.key === 'reviewed') {
		interval.value = countDay(props.deadline, timer, interval.value);
	} else {
		interval.value = countDay(props.deadline, timer);
	}
});

const init = () => {
	store.commit('spinner/toggleSpinning', 'drawer');
};

defineExpose({ init });
</script>
<style scoped></style>
