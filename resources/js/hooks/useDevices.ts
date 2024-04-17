import { ref } from 'vue';
import api from '@/libs/api';
import type { IUseDevices } from '@/contracts/device/IUseDevices';
import type { IDevices } from '@/contracts/device/IDevices';
import type { IDevice } from '@/contracts/device/IDevice';
import type { IUploadPayload } from '@/contracts/factory-device/IUploadPayload';

export const useDevices = (defaultDevices: IDevice[] = []): IUseDevices => {
	const devices = ref<IDevice[]>(defaultDevices);
	const total = ref<number | null>(null);
	const page = ref<number | null>(null);
	const perPage = ref<number | null>(null);

	const getDevices = async (filters: any) => {
		await api.apis.getDevices({ ...filters }, onSuccess, onError);
	};

	const onSuccess = ({ data: response }: { data: IDevices }) => {
		devices.value = response.data;
		total.value = response.meta?.total || null;
		page.value = response.meta?.current_page || null;
		perPage.value = response.meta?.per_page || null;
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
