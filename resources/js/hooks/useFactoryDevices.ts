import { onBeforeMount, ref } from 'vue';
import api from '@/libs/api';
import type { IUploadPayload } from '@/contracts/factory-device/IUploadPayload';
import { success } from 'toastr';
import store from '@/store';

export function useFactoryDevices(isFetch: boolean = true) {
	const devices = ref([]);
	const isLoading = ref(true);

	const fetching = async () => {
		api.apis.getFactoryDevices();
	};

	if (isFetch) {
		onBeforeMount(fetching);
	}

	const createDevice = async (payload: IUploadPayload) => {
		const onSuccess = ({ data: response }) => {
			success(response.message);

			store.commit('spinner/toggleSpinning', 'main');
			store.commit('factory/setIsReload', true);
		};

		await api.apis.createFactoryDevice(payload, onSuccess);
	};

	return {
		devices,
		isLoading,
		createDevice,
	};
}

// createDevice: (payload: IUploadPayload) => Promise<void>;
