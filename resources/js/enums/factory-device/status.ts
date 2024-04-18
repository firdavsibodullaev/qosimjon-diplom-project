enum status {
	active,
	inactive,
}

const list = {
	[status.active]: 'Soz',
	[status.inactive]: 'Nosoz',
};

const translation = {
	[status.active]: 'Soz',
	[status[status.active]]: 'Soz',
	[status.inactive]: 'Nosoz',
	[status[status.inactive]]: 'Nosoz',
};

export { status, translation, list };
