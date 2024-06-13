import { onMounted, ref } from 'vue';
import api from '@/libs/api';
import { useRoute, useRouter } from 'vue-router';

export const useApplication = (filters?: object) => {
	const { isLoad, isLoop } = filters;

	const route = useRoute();
	const router = useRouter();
	const applications = ref([]);
	const isLoading = ref(true);
	const interval = ref<number | null>(null);
	const searchQuery = ref(route.query.q || '');
	const canRequestServer = ref(true);
	const pagination = ref({
		total: 0,
		current: route.query.page || 1,
		pageSize: route.query.page_size || 0,
	});

	const fetch = async (filter = {}) => {
		await api.apis.getApplication(
			{
				page: pagination.value.current,
				q: searchQuery.value || '',
				...filter,
			},
			({ data }) => {
				applications.value = data!.data;

				pagination.value = {
					total: data.meta.total,
					current: applications.value.length
						? data.meta.current_page
						: data.meta.last_page,
					pageSize: data.meta.per_page,
				};

				if (!applications.value.length) {
					canRequestServer.value = false;
					fetch(filter).finally(
						() => (canRequestServer.value = true),
					);
				}
			},
			(response) => {},
		);

		await router.push({
			name: route.name,
			query: { q: searchQuery.value, page: pagination.value.current },
		});
		isLoading.value = false;
	};

	const fetchLooped = async () => {
		if (interval.value) {
			clearInterval(interval.value);
		}
		interval.value = setInterval(() => {
			if (!canRequestServer.value) return;

			canRequestServer.value = false;
			fetch().finally(() => (canRequestServer.value = true));
		}, 5000);
	};

	const handleTableChange = (page: any) => {
		isLoading.value = true;

		canRequestServer.value = false;

		pagination.value = {
			total: page.pageSize,
			current: page.current,
			pageSize: pagination.value.pageSize,
		};

		fetch().finally(() => (canRequestServer.value = true));
	};

	isLoad && (isLoop ? onMounted(fetchLooped) : onMounted(fetch));

	return {
		applications,
		fetch,
		isLoading,
		searchQuery,
		canRequestServer,
		pagination,
		handleTableChange,
	};
};
