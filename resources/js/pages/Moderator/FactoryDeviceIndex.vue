<template>
	<Layout page-title="Priborni qo'shish">
		<a-button
			class="bg-ant-primary mb-4"
			type="primary"
			@click="
				openWithComponent(Types.Create, 'create', 'Pribor qo\'shish')
			"
			>Pribor qo'shish
		</a-button>
		<a-table
			:loading="isLoading"
			:columns="columns"
			:data-source="data"
			:pagination="pagination"
			:scroll="{ y: 530 }"
			@change="handleTableChange"
		>
			<template #bodyCell="{ column, text, record }">
				<template v-if="column.dataIndex === 'actions'">
					<a-button
						type="primary"
						@click="
							openWithComponent(
								Types.Show,
								`show-${record.record.full_number}-${record.record.id}`,
								'Batafsil',
								record.record,
							)
						"
						class="mr-2 bg-ant-primary"
						>Batafsil
					</a-button>
					<a-button
						type="primary"
						:id="`record-${record.record.id}`"
						@click="
							openWithComponent(
								Types.Edit,
								`edit-${record.record.full_number}-${record.record.id}`,
								'Priborni tahrirlash',
								record.record,
							)
						"
						class="mr-2 bg-yellow-400 text-black hover:!bg-yellow-300 hover:!text-black"
						>Tahrirlash
					</a-button>
					<!--					<a-popconfirm-->
					<!--						v-if="data.length"-->
					<!--						title="Ma'umotni o'chirmoqchimisiz?"-->
					<!--					>-->
					<!--						<template #okButton>-->
					<!--							<a-button-->
					<!--								@click="onDelete(record.id)"-->
					<!--								danger-->
					<!--								type="primary"-->
					<!--								>O'chirish-->
					<!--							</a-button>-->
					<!--						</template>-->
					<!--						<a-button type="primary" danger>O'chirish</a-button>-->
					<!--					</a-popconfirm>-->
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
import { useDrawers } from '@/hooks/useDrawers';
import { computed, markRaw } from 'vue';
import FactoryDeviceCreate from '@/pages/Moderator/FactoryDeviceCreate.vue';
import FactoryDeviceEdit from '@/pages/Moderator/FactoryDeviceEdit.vue';
import FactoryDeviceShow from '@/pages/Moderator/FactoryDeviceShow.vue';
import Types from '@/enums/drawer/Types';
import Drawer from '@/components/Drawer.vue';
import { useFactoryDevices } from '@/hooks/useFactoryDevices';
import { translation } from '@/enums/factory-device/position';
import { translation as statusTranslation } from '@/enums/factory-device/status';

const { devices, isLoading, pagination, handleTableChange } =
	useFactoryDevices();
const { openDrawer, componentKey, component } = useDrawers(
	markRaw(FactoryDeviceCreate),
	markRaw(FactoryDeviceEdit),
	markRaw(FactoryDeviceShow),
);

const data = computed(() =>
	devices.value.map((device, index) => ({
		orderNumber: index + 1,
		id: device.id,
		name: `${device.device.name} (${device.full_number})`,
		status: statusTranslation[device.status],
		position: translation[device.position],
		record: device,
	})),
);
const columns = [
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
		title: 'Nomi',
		dataIndex: 'name',
		width: 200,
	},
	{
		title: 'Holati',
		dataIndex: 'status',
		width: 200,
	},
	{
		title: 'Joyi',
		dataIndex: 'position',
		width: 200,
	},
	{
		title: '',
		dataIndex: 'actions',
		width: 200,
	},
];
const openWithComponent = (
	type: Types,
	key: string,
	title: string,
	props?: object,
) => {
	openDrawer(type, key, title, props);
};
</script>
