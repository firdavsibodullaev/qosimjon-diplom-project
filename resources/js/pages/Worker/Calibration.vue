<template>
	<Layout page-title="O'lchov vositalarini qiyoslashga berish">
		<div class="flex gap-4">
			<a-button
				class="bg-ant-primary mb-4"
				type="primary"
				@click="
					openWithComponent(Types.Create, 'create', 'Ariza yaratish')
				"
				>Ariza yaratish
			</a-button>
			<div>
				<a-input-search
					v-model:value="searchText"
					placeholder="Qidirish"
					enter-button
					@search="onSearch"
				/>
			</div>
		</div>
		<a-table
			:columns="columns"
			:loading="isLoading"
			:data-source="data"
			:pagination="pagination"
			:scroll="{ y: 530 }"
		>
			<template #bodyCell="{ column, text, record }">
				<template v-if="column.dataIndex === 'document'">
					<a
						:href="record.document"
						class="bg-blue-600 text-white rounded px-2 py-1 hover:bg-blue-700 hover:text-gray-300 active:bg-blue-800 active:text-gray-400"
						target="_blank"
						>Hujjat
					</a>
				</template>
				<template v-if="column.dataIndex === 'actions'">
					<a-button
						type="primary"
						@click="
							openWithComponent(
								Types.Show,
								`show-${record.id}`,
								`Ariza №${record.id}`,
								record.record,
							)
						"
						class="mr-2 bg-ant-primary"
						>Batafsil
					</a-button>
					<a-button
						type="primary"
						:id="`record-${1}`"
						:disabled="record.record.status.key !== 'new'"
						@click="
							openWithComponent(
								Types.Edit,
								`show-${record.id}`,
								'Arizani tahrirlash',
								record.record,
							)
						"
						:class="`mr-2 bg-yellow-400 text-black ${record.record.status.key === 'new' ? 'hover:!bg-yellow-300 hover:!text-black' : ''}`"
						>Tahrirlash
					</a-button>
				</template>
			</template>
		</a-table>
		<Drawer
			:width="850"
			key="Drawer"
			:componentKey="componentKey"
			:component="component"
		/>
	</Layout>
</template>
<script setup lang="ts">
import Layout from '@/pages/Layout.vue';
import { computed, markRaw, ref } from 'vue';
import Types from '@/enums/drawer/Types';
import { useDrawers } from '@/hooks/useDrawers';
import CalibrationCreate from '@/pages/Worker/CalibrationCreate.vue';
import CalibrationEdit from '@/pages/Worker/CalibrationEdit.vue';
import CalibrationShow from '@/pages/Worker/CalibrationShow.vue';
import Drawer from '@/components/Drawer.vue';
import { useCalibration } from '@/hooks/calibration/useCalibration';
import formatDate from '@/utils/formatDate';
import { useRoute } from 'vue-router';

const { applications, isLoading, pagination, getApplications } =
	useCalibration();
const { openDrawer, componentKey, component } = useDrawers(
	markRaw(CalibrationCreate),
	markRaw(CalibrationEdit),
	markRaw(CalibrationShow),
);
const route = useRoute();
const columns = ref([
	{
		title: '№',
		dataIndex: 'orderNumber',
		width: 40,
	},
	{
		title: 'ID',
		dataIndex: 'id',
		width: 40,
		sorter: true,
	},
	{
		title: 'Pribor nomi',
		dataIndex: 'name',
		width: 200,
	},
	{
		title: 'Arizachi korxona',
		dataIndex: 'applicant',
		width: 200,
	},
	{
		title: 'Tekshiruvchi korxona',
		dataIndex: 'checker',
		width: 200,
	},
	{
		title: 'Ariza berilgan vaqt',
		dataIndex: 'created_at',
		width: 200,
	},
	{
		title: 'Ariza holati',
		dataIndex: 'status',
		width: 200,
	},
	{
		title: 'Hujjat',
		dataIndex: 'document',
		width: 200,
	},
	{
		title: '',
		dataIndex: 'actions',
		width: 200,
	},
]);
const searchText = ref(route.query.q || '');

const data = computed(() =>
	applications.value.map((item, index) => ({
		record: item,
		orderNumber: index + 1,
		id: item.id,
		name: `${item.device.device.name} (${item.device.full_number})`,
		applicant: `${item.applicantFactory.name} (${item.applicantFactory.number})`,
		checker: item.checkerFactory.name,
		created_at: formatDate(item.created_at),
		status: item.status.value,
		document: item.document.url,
	})),
);

const onSearch = (value: string) => {
	getApplications({ q: value });
};

const openWithComponent = (
	type: Types,
	key: string,
	title: string,
	props?: object,
) => {
	openDrawer(type, key, title, props);
};
</script>
<style scoped></style>
