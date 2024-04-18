import { computed, onBeforeMount, ref, watch } from 'vue';
import api from '@/libs/api';
import type { IUploadPayload } from '@/contracts/factory-device/IUploadPayload';
import { success } from 'toastr';
import store from '@/store';
import type { ISuccessResponse } from '@/contracts/factory-device/ISuccessResponse';
import type { IErrorResponse } from '@/contracts/factory-device/IErrorResponse';
import showValidationErrors from '@/utils/showValidationErrors';
import type { IOnSuccess } from '@/contracts/api/IOnSuccess';
import type { IFactoryDevices } from '@/contracts/factory-device/IFactoryDevices';
import type { IFactoryDevice } from '@/contracts/factory-device/IFactoryDevice';
import makeSorterField from '@/utils/makeSorterField';
import { useRoute, useRouter } from 'vue-router';

export function useFactoryDevices(isFetch: boolean = true) {
	const route = useRoute();
	const router = useRouter();
	const devices = ref<IFactoryDevice[]>([]);
	const pagination = ref({
		total: 0,
		current: route.query.page || 1,
		pageSize: route.query.page_size || 0,
	});
	const sorter = ref<string | null>(route.query.sorter);
	const isLoading = ref(true);
	const isReloadPage = computed(() => store.getters['factory/getIsReload']);
	const getDevices = async (
		filters: { [key: string]: string | null | number } = {},
	) => {
		sorter.value = <string | null>filters.sorter;
		api.apis.getFactoryDevices(filters, onSuccess);
	};

	const onSuccess: IOnSuccess = (response) => {
		const data = <IFactoryDevices>response.data;

		if (!data.data.length && data.meta.current_page !== 1) {
			getDevices({
				page: data.meta.last_page,
				total: data.meta.total,
				sorter: sorter.value,
			});
			return;
		}

		devices.value = data.data;
		pagination.value = {
			total: data.meta.total,
			current: data.meta.current_page,
			pageSize: data.meta.per_page,
		};

		isLoading.value = false;

		router.push({
			name: 'factoryDevices',
			query: {
				total: pagination.value.pageSize,
				page: pagination.value.current,
				sorter: sorter.value,
			},
		});
	};

	isFetch &&
		onBeforeMount(() =>
			getDevices({
				page: pagination.value.current,
				total: pagination.value.pageSize,
				sorter: sorter.value,
			}),
		);

	const createDevice = async (payload: IUploadPayload) =>
		await api.apis.createFactoryDevice(payload, onSaveSuccess, onSaveError);

	const updateDevice = async (id: number, payload: IUploadPayload) =>
		await api.apis.updateFactoryDevice(
			id,
			payload,
			onSaveSuccess,
			onSaveError,
		);

	const onSaveSuccess = ({ data: response }: ISuccessResponse) => {
		success(response.message);
		store.commit('spinner/toggleSpinning', 'main');
		store.commit('factory/setIsReload', true);
		store.dispatch('drawer/clearDrawer');
	};

	const onSaveError = ({ response }: { response: IErrorResponse }) => {
		const { status, data } = response;
		if (status === 422 && data.errors) {
			showValidationErrors(data.errors);
		}
	};

	const handleTableChange = (page, filters, sorter) => {
		isLoading.value = true;

		getDevices({
			page: page.current,
			total: page.pageSize,
			sorter: makeSorterField(sorter.field, sorter.order),
		});
	};

	watch(isReloadPage, (newValue) => {
		if (newValue) {
			isLoading.value = true;
			getDevices({
				page: pagination.value.current,
				sorter: sorter.value,
			});
			store.commit('factory/setIsReload', false);
		}
	});

	watch(isLoading, (newValue) =>
		store.commit('sidebar/setLoading', newValue),
	);

	return {
		devices,
		isLoading,
		pagination,
		getDevices,
		createDevice,
		updateDevice,
		handleTableChange,
	};
}

// createDevice: (payload: IUploadPayload) => Promise<void>;
