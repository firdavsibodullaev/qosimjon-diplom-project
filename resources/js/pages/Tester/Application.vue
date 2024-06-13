<template>
	<Layout page-title="O'lchov vositalarini qiyoslashga berilgan arizalar">
		<div class="flex gap-4">
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
			@change="handleTableChange"
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
								Types.Edit,
								`check-${record.id}`,
								`Ariza №${record.id}`,
								record.record,
							)
						"
						class="mr-2 bg-ant-primary"
						>Batafsil
					</a-button>
				</template>
			</template>
		</a-table>
		<Drawer
			:width="1000"
			key="Drawer"
			padding-bottom="0"
			:componentKey="componentKey"
			:component="component"
		/>
	</Layout>
</template>
<script setup lang="ts">
import Layout from '@/pages/Layout.vue';
import { computed, markRaw, ref } from 'vue';
import formatDate from '@/utils/formatDate';
import { useRoute } from 'vue-router';
import { useApplication } from '@/hooks/application/useApplication';
import { useDrawers } from '@/hooks/useDrawers';
import Check from '@/pages/Tester/Check.vue';
import Drawer from '@/components/Drawer.vue';
import Types from '@/enums/drawer/Types';

const { openDrawer, componentKey, component } = useDrawers(
	undefined,
	markRaw(Check),
	undefined,
);
const {
	fetch,
	applications,
	isLoading,
	searchQuery,
	canRequestServer,
	pagination,
	handleTableChange,
} = useApplication({
	isLoad: true,
	isLoop: true,
});

const route = useRoute();
const columns = ref([
	{
		title: '№',
		dataIndex: 'orderNumber',
		width: 50,
	},
	{
		title: 'ID',
		dataIndex: 'id',
		width: 50,
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

const onSearch = () => {
	isLoading.value = true;
	canRequestServer.value = false;
	searchQuery.value = searchText.value;
	fetch().finally(() => (canRequestServer.value = true));
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
