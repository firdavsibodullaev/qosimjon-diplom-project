import api from '@/libs/api';
import toastr from 'toastr';
import store from '@/store';

export const useCalibrationCreate = () => {
	const create = async (payload) => {
		await api.apis.createCalibration(payload, onSuccess, onError);
	};

	const edit = async (id: number, payload) => {
		await api.apis.updateApplication(id, payload, onSuccess, onError);
	};

	const onSuccess = ({ data }) => {
		store.dispatch('drawer/clearDrawer');
		store.commit('spinner/toggleSpinning', 'main');
		store.commit('factory/setIsReload', true);
		toastr.success(data.message);
	};

	const onError = ({ response }) => {
		const { data, status: code } = response;

		const { status, message } = data;

		toastr.error(message);
		store.commit('spinner/toggleSpinning', 'main');
	};

	return {
		create,
		edit,
	};
};
