import { computed, onMounted, ref, watch } from 'vue';
import api from '@/libs/api';
import { useRoute, useRouter } from 'vue-router';
import store from '@/store';

export const useCalibration = (isFetch: boolean = true, filters = {}) => {
	const route = useRoute();
	const router = useRouter();

	const applications = ref([]);
	const isLoading = ref<boolean>(true);
	const pagination = ref({
		total: 0,
		current: route.query.page || 1,
		pageSize: route.query.page_size || 0,
	});

	const isPageReload = computed<boolean>(
		() => store.getters['factory/getIsReload'],
	);

	const getCalibrations = async (filter = {}) => {
		isLoading.value = true;
		await api.apis.getCalibration(
			filter,
			({ data }) => onSuccess({ data }, filter),
			onError,
		);
	};

	const onSuccess = ({ data }, filter) => {
		applications.value = data.data;
		isLoading.value = false;
		pagination.value = {
			total: data.meta.total,
			current: data.meta.current_page,
			pageSize: data.meta.per_page,
		};

		store.commit('factory/setIsReload', false);

		router.push({
			name: route.name,
			query: {
				page: pagination.value.current,
				q: filter.q || '',
			},
		});
	};

	const onError = (response) => {
		isLoading.value = false;
	};

	watch(isPageReload, (newValue) => newValue && getCalibrations(filters));

	isFetch && onMounted(() => getCalibrations(filters));

	return {
		isLoading,
		applications,
		pagination,
		getCalibrations,
	};
};
