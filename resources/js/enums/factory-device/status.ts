enum status {
	active,
	inactive,
}

const translation = {
	[status.active]: 'Soz',
	[status.inactive]: 'Nosoz',
};

export { status, translation };
