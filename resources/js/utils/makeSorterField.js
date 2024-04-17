export default (field, order = null) => {
	if (!field || !order) {
		return null;
	}

	return (order !== 'ascend' ? '-' : '') + field;
};
