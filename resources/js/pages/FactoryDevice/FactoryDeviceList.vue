<script lang="ts">
import { defineComponent, markRaw } from 'vue';
import Layout from '@/pages/Layout.vue';
import Drawer from '@/components/Drawer.vue';
import api from '@/libs/api';
import formatDate from '@/utils/formatDate';
import FactoryDeviceShow from '@/pages/FactoryDevice/FactoryDeviceShow.vue';
import { getDateDifference } from '@/utils/getDateDifference';

const columns = [
	{
		title: '№',
		dataIndex: 'orderNumber',
		width: 15,
	},
	{
		title: 'ID',
		dataIndex: 'id',
		width: 20,
		sorter: true,
	},
	{
		title: 'Nomi',
		dataIndex: 'name',
		width: 70,
	},
	{
		title: 'Zavod',
		dataIndex: 'factory',
		width: 100,
	},
	{
		title: 'Sex',
		dataIndex: 'floor',
		width: 100,
	},
	{
		title: "Oxirgi marta tekshiruvdan o'tkazilgan vaqt",
		dataIndex: 'last_checked_at',
		width: 100,
	},
	{
		title: '',
		dataIndex: 'actions',
		width: 50,
	},
];

export default defineComponent({
	name: 'FactoryDeviceList',
	components: { Drawer, Layout },
	data() {
		return {
			loading: true,
			columns: columns,
			data: [],
			pagination: {},
			component: null,
			componentKey: '',
		};
	},
	methods: {
		async fetch(filters?: object) {
			await api.apis.getFactoryDevices(
				{ 'with-calibration': 1, 'all-calibrations': 1, ...filters },
				({ data }) => {
					const { data: jsonData, meta } = data;

					this.data = jsonData.map((item, index) => ({
						orderNumber: index + 1,
						id: item.id,
						name: `${item.device.name} (${item.full_number})`,
						factory: `${item.factory.name} (${item.factory.number})`,
						floor: `${item.factory_floor.name} (${item.factory_floor.number})`,
						last_checked_at:
							formatDate(item.last_checked_at, false) || '—',
						is_too_close_to_check:
							item.last_checked_at && item.check_every_time
								? getDateDifference(
										item.last_checked_at,
										this.nextCheckDate(
											item.last_checked_at,
											item.check_every_time,
										),
									) < 50
								: false,
						record: item,
					}));
				},
				(data) => {
					console.log(data);
				},
			);
			this.loading = false;
		},
		nextCheckDate(fromDate: null | string, months: number) {
			const date = new Date(fromDate);
			date.setMonth(date.getMonth() + months);
			return date;
		},
		handleTableChange() {},
		onDelete(id: number) {},
		openWithComponent(name: string, record) {
			this.componentKey = `device-${name}-${record.id}`;
			this.component = markRaw(FactoryDeviceShow);
			this.$store.commit('drawer/setComponentProps', record);
			this.$store.commit('drawer/setDrawerTitle', record.device.name);
			this.$store.commit('drawer/setOpen', true);
		},
	},
	mounted() {
		this.fetch({ sorter: '-last_checked_at,id', 'checking-important': 1 });
	},
});
</script>

<template>
	<Layout page-title="Priborlar ro'yhati">
		<a-table
			:loading="loading"
			:columns="columns"
			:data-source="data"
			:pagination="pagination"
			:scroll="{ y: 560 }"
			@change="handleTableChange"
		>
			<template class="321321" #bodyCell="{ column, text, record }">
				<template v-if="column.dataIndex === 'orderNumber'">
					<span
						:class="`${record.is_too_close_to_check && 'red-bg'}`"
						>{{ text }}</span
					>
				</template>
				<template v-if="column.dataIndex === 'actions'">
					<a-button
						type="primary"
						@click="openWithComponent('show', record.record)"
						class="mr-2 bg-ant-primary"
						>Batafsil
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
<style scoped>
div:deep(tr:has(td > span.red-bg)) {
	background-color: #f3c3c3;
}
</style>
