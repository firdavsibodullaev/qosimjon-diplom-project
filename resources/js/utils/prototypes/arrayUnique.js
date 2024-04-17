export default () => {
	return function () {
		return [...new Set(this)];
	};
};
