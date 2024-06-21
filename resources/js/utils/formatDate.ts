export default (
	dateTime: string | null | Date,
	withTime: boolean = true,
): null | string => {
	if (!dateTime) {
		return null;
	}

	const date = typeof dateTime === 'object' ? dateTime : new Date(dateTime);
	const day = date.getDate();
	const month = date.getMonth();
	const year = date.getFullYear();

	if (!withTime) {
		return `${formatNumber(day)}.${formatNumber(month + 1)}.${formatNumber(year)}`;
	}

	const hours = date.getHours();
	const minutes = date.getMinutes();
	const seconds = date.getSeconds();

	return `${formatNumber(day)}.${formatNumber(month + 1)}.${formatNumber(year)} ${formatNumber(hours)}:${formatNumber(minutes)}:${formatNumber(seconds)}`;
};

const formatNumber = (value: number) => (value < 10 ? `0${value}` : value);
