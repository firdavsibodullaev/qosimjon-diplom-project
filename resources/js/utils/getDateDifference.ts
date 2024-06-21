export const getDateDifference = (dateTime: string, differenceDate: Date) => {
	const date = new Date(dateTime);
	const oneDayInMilliseconds = 1000 * 60 * 60 * 24;
	const timeDifferenceMs = Math.abs(differenceDate - date);

	return Math.round(timeDifferenceMs / oneDayInMilliseconds);
};
