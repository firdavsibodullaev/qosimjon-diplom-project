export default () => {
	Array.prototype.unique = function (
		key?: string | ((array: any[]) => any[]),
	): any[] {
		if (!key) {
			return [...new Set(this)];
		}

		if (typeof key === 'function') {
			return key(this);
		}

		const seen = new Set();

		return this.filter((item) => {
			const value: any = item[key];

			if (seen.has(value)) {
				return false;
			}

			seen.add(value);
			return true;
		});
	};
};
