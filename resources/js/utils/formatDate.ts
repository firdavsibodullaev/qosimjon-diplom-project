export default (dateTime: string) => {
	const date = new Date(dateTime);
	const day = date.getDate();
	const month = date.getMonth();
	const year = date.getFullYear();
	const hours = date.getHours();
	const minutes = date.getMinutes();
	const seconds = date.getSeconds();

	return `${formatNumber(day)}.${formatNumber(month + 1)}.${formatNumber(year)} ${formatNumber(hours)}:${formatNumber(minutes)}:${formatNumber(seconds)}`;
};

const formatNumber = (value: number) => (value < 10 ? `0${value}` : value);
