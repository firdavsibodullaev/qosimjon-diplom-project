<template>
	<span
		v-if="document && document.url"
		class="rounded border border-dashed border-gray-400 px-2 py-1"
	>
		<a target="_blank" :href="document.url">
			{{ formatName(document.name) }}
			<span class="ml-1 text-gray-500"> ({{ document.size }}) </span>
		</a>
	</span>
</template>

<script setup lang="ts">
defineProps<{
	document?: { url: string | null; name: string; size: string } | null;
}>();

const formatName = (name: string) => {
	if (name.length < 22) {
		return name;
	}

	let newName: string | string[] = name.split('.');

	let extension = <string>newName.pop();

	newName = newName.join('.').substring(0, 22 - (extension.length + 5));

	return `${newName}....${extension}`;
};
</script>
