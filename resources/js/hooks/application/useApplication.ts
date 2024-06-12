import { onMounted, ref } from 'vue';
import api from '@/libs/api';

export const useApplication = (filters?: object) => {
	const { isLoad, isLoop } = filters;

	const applications = ref([]);
	const isLoading = ref(true);
	const interval = ref<number | null>(null);

	const fetch = async (filter = {}) => {
		await api.apis.getApplication(
			filter,
			({ data }) => {
				applications.value = data!.data;
			},
			(response) => {},
		);

		isLoading.value = false;
	};

	const fetchLooped = async () => {
		if (interval.value) {
			clearInterval(interval.value);
		}
		let canRequestServer = true;
		interval.value = setInterval(() => {
			if (!canRequestServer) return;

			canRequestServer = false;
			fetch().finally(() => (canRequestServer = true));
		}, 5000);
	};

	isLoad && (isLoop ? onMounted(fetchLooped) : onMounted(fetch));

	return {
		applications,
		fetch,
		isLoading,
	};
};
