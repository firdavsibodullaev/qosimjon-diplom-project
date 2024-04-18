import { error } from 'toastr';

export default (errors: { [key: string]: string[] }) => {
	return Object.keys(errors).forEach((key) => error(errors[key][0]));
};
