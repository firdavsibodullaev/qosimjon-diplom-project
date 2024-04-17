import { onBeforeMount, onMounted, ref } from 'vue';
import api from '@/libs/api';

export function useFactoryDevices() {
	const devices = ref([]);
	const isLoading = ref(true);

	const fetching = async () => {
		api.apis.getFactoryDevices();
	};

	onBeforeMount(fetching);

	return {
		devices,
		isLoading,
	};
}
