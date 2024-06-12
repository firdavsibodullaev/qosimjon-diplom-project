import type { Ref } from 'vue';

export const countDay = (dateTime: string, timer: Ref, interval?: number) => {
	const considerTime = new Date(dateTime);
	const considerTimestamp = considerTime.getTime();

	if (interval) {
		clearInterval(interval);
		return;
	}

	interval = setInterval(() => {
		const now = new Date();
		const nowTimestamp = now.getTime();

		let difference = considerTimestamp - nowTimestamp;

		if (difference < 0) {
			interval && clearInterval(interval);
			timer.value = 'Muddati tugadi';
			return;
		}

		const days = Math.floor(difference / (1000 * 60 * 60 * 24));
		const hours = Math.floor(
			(difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60),
		);
		const minutes = Math.floor(
			(difference % (1000 * 60 * 60)) / (1000 * 60),
		);
		const seconds = Math.floor((difference % (1000 * 60)) / 1000);
		timer.value = `${formatNumber(days)} kun ${formatNumber(hours)}s ${formatNumber(minutes)}m ${formatNumber(seconds)} soniya qoldi`;
	}, 1000);

	return interval;
};

const formatNumber = (value: number) => (value < 10 ? `0${value}` : value);
