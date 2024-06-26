export default function (
	params?: Record<string, string | number | boolean | null> | object,
) {
	if (!params) {
		return '';
	}

	return Object.keys(params)
		.filter((key) => params[key])
		.map((key) => `${key}=${params[key]}`)
		.join('&');
}
