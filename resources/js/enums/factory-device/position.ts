enum position {
	warehouse,
	'on-place',
	checking,
}

const list = {
	[position.warehouse]: 'Omborxonada',
	[position['on-place']]: 'Transpluatatsiya joyida',
	[position.checking]: 'Tekshirishda',
};

const translation = {
	[position.warehouse]: 'Omborxonada',
	[position[position.warehouse]]: 'Omborxonada',
	[position['on-place']]: 'Transpluatatsiya joyida',
	[position[position['on-place']]]: 'Transpluatatsiya joyida',
	[position.checking]: 'Tekshirishda',
	[position[position.checking]]: 'Tekshirishda',
};

export { position, translation, list };
