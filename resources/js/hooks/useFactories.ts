import { computed, onBeforeMount, ref } from 'vue';
import api from '@/libs/api';
import store from '@/store';
import type { IOnSuccess } from '@/contracts/api/IOnSuccess';
import { useRoute, useRouter } from 'vue-router';
import type { IFactory } from '@/contracts/factory/IFactory';
import type { IFactoryList } from '@/contracts/factory/IFactoryList';

export function useFactories(isFetch: boolean = true, filter?: object) {
	const route = useRoute();
	const router = useRouter();
	const factories = ref<IFactory[]>([]);
	const pagination = ref({
		total: 0,
		current: route.query.page || 1,
		pageSize: route.query.page_size || 0,
	});
	const sorter = ref<string | null>(route.query.sorter?.toString || '');
	const isLoading = ref(true);
	const isReloadPage = computed(() => store.getters['factory/getIsReload']);
	const getFactories = async (
		filters: { [key: string]: string | null | number } | object = {},
	) => {
		sorter.value = <string | null>filters.sorter;
		await api.apis.getFactories(filters, onSuccess);
	};

	const onSuccess: IOnSuccess = (response) => {
		const data = <IFactoryList>response.data;

		if (!data.data.length && data.meta!.current_page !== 1) {
			getFactories({
				page: data.meta?.last_page || null,
				total: data.meta?.total || null,
				sorter: sorter.value,
			});
			return;
		}

		factories.value = data.data;
		pagination.value = {
			total: data.meta?.total || 20,
			current: data.meta?.current_page || 1,
			pageSize: data.meta?.per_page || 15,
		};

		isLoading.value = false;

		router.push({
			name: route.name,
			query: {
				total: pagination.value.pageSize,
				page: pagination.value.current,
				sorter: sorter.value,
			},
		});
	};

	isFetch &&
		onBeforeMount(() =>
			getFactories(
				filter ?? {
					page: pagination.value.current,
					total: pagination.value.pageSize,
					sorter: sorter.value,
				},
			),
		);

	return {
		factories,
		isLoading,
		pagination,
		getFactories,
	};
}
