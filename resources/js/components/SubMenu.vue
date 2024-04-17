<template>
	<a-sub-menu :key="key">
		<template #title>
			<span>{{ text }}</span>
		</template>
		<div
			v-for="(route, index) in routes"
			:key="`sidebar-menu-item-${index}`"
		>
			<MenuItem
				v-if="!route.children || !route.children.length"
				:key="`menu-item-${route.name}`"
			>
				<span>{{ route.text }}</span>
			</MenuItem>
			<SubMenu
				:text="route.text"
				v-else
				:key="`sub-menu-${route.name}`"
				:routes="route.children"
			/>
		</div>
	</a-sub-menu>
</template>
<script>
import MenuItem from '@/components/MenuItem.vue';

export default {
	name: 'SubMenu',
	components: { MenuItem },
	props: {
		key: {
			type: String,
			required: true,
		},
		text: {
			type: String,
			required: true,
		},
		routes: {
			type: Array,
			required: true,
		},
	},
};
</script>
