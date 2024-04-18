import { ref } from 'vue';
import api from '@/libs/api';
import type { IUseDevices } from '@/contracts/device/IUseDevices';
import type { IDevice } from '@/contracts/device/IDevice';
import type { IOnSuccessWrapper } from '@/contracts/api/IOnSuccess';
import type { IDevices } from '@/contracts/device/IDevices';

export const useDevices = (defaultDevices: IDevice[] = []): IUseDevices => {
	const devices = ref<IDevice[]>(defaultDevices);
	const total = ref<number | null>(null);
	const page = ref<number | null>(null);
	const perPage = ref<number | null>(null);

	const getDevices = async (filters: any) => {
		await api.apis.getDevices({ ...filters }, onSuccess, onError);
	};

	const onSuccess = (response: IOnSuccessWrapper) => {
		const data = <IDevices>response.data;

		devices.value = data.data;
		total.value = data.meta?.total || null;
		page.value = data.meta?.current_page || null;
		perPage.value = data.meta?.per_page || null;
	};

	const onError = null;

	return {
		devices,
		total,
		page,
		perPage,
		getDevices,
	};
};
