<template>
	<div class="mt-4" v-if="data">
		<div class="grid grid-cols-2 gap-6 mb-4">
			<div class="text-lg"><strong>Nomi:</strong></div>
			<div class="text-lg">{{ data.name }}</div>
		</div>
		<div class="grid grid-cols-2 gap-6 mb-4">
			<div class="text-lg"><strong>Raqami:</strong></div>
			<div class="text-lg">{{ data.number }}</div>
		</div>
		<div class="grid grid-cols-2 gap-6 mb-4">
			<div class="text-lg"><strong>Turi:</strong></div>
			<div class="text-lg">{{ types[data.type] }}</div>
		</div>
		<hr />
		<div class="mt-10">
			<p class="text-xl"><strong>Sexlar</strong></p>
			<a-table
				:columns="columns"
				:pagination="false"
				:data-source="floors"
				:scroll="{ y: 430 }"
			/>
		</div>
	</div>
	<div v-else class="min-h-screen"></div>
</template>
<script>
import factoryType from '@/pages/Admin/Factory/factoryType';

export default {
	name: 'FactoryShow',
	props: {
		id: {
			type: Number,
		},
	},
	data() {
		return {
			columns: [
				{
					title: '№',
					dataIndex: 'orderNumber',
					width: 20,
				},
				{
					title: 'Nomi',
					dataIndex: 'name',
					width: 150,
				},
				{
					title: 'Raqami',
					dataIndex: 'number',
					width: 150,
				},
			],
			data: null,
			types: factoryType,
		};
	},
	computed: {
		closeDrawer() {
			return this.$store.getters['drawer/getOpen'];
		},
		floors() {
			return this.data.floors.map((floor, index) => {
				return {
					orderNumber: index + 1,
					name: floor.name,
					number: floor.number,
				};
			});
		},
	},
	methods: {
		onClose() {
			this.$store.dispatch('drawer/clearDrawer');
		},
		getFactory() {
			this.$api.getFactory(
				this.id,
				({ data }) => {
					this.data = data.data;
					this.$store.commit('spinner/toggleSpinning', 'drawer');
				},
				(error) => {
					this.$store.commit('spinner/toggleSpinning', 'drawer');
				},
			);
		},
		init() {
			if (!this.id) {
				this.onClose();
				this.$store.commit('spinner/toggleSpinning', 'drawer');

				return;
			}

			this.getFactory();
		},
	},
	watch: {
		closeDrawer(newValue) {
			if (!newValue) {
				if (this.$store.getters[`spinner/getDrawerSpinning`]) {
					this.$store.commit('spinner/toggleSpinning', 'drawer');
				}
				this.data = null;
			}
		},
	},
};
</script>
