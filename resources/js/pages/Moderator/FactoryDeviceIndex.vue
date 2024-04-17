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
import { markRaw } from 'vue';
import FactoryDeviceCreate from '@/pages/Moderator/FactoryDeviceCreate.vue';
import FactoryDeviceEdit from '@/pages/Moderator/FactoryDeviceEdit.vue';
import FactoryDeviceShow from '@/pages/Moderator/FactoryDeviceShow.vue';
import Types from '@/enums/drawer/Types';
import Drawer from '@/components/Drawer.vue';
import { useFactoryDevices } from '@/hooks/useFactoryDevices';

// const { devices, isLoading } = useFactoryDevices();

const { openDrawer, componentKey, component } = useDrawers(
	markRaw(FactoryDeviceCreate),
	markRaw(FactoryDeviceEdit),
	markRaw(FactoryDeviceShow),
);

const openWithComponent = (
	type: Types,
	key: string,
	title: string,
	props?: object,
) => {
	openDrawer(type, key, title, props);
};
</script>
