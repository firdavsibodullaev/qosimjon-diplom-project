enum position {
	warehouse,
	'on-place',
	checking,
}

const translation = {
	[position.warehouse]: 'Omborxonada',
	[position['on-place']]: 'Transpluatatsiya joyida',
	[position.checking]: 'Tekshirishda',
};

export { position, translation };
