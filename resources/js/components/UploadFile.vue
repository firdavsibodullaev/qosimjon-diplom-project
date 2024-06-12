<template>
	<div>
		<label
			class="flex rounded border border-dashed border-gray-500 cursor-pointer px-3 py-1 w-fit duration-200 transition-all hover:bg-gray-200 hover:border-gray-800 active:bg-gray-300 active:border-gray-900"
			:for="id"
		>
			<span class="flex items-center"><slot name="icon" /></span>
			<slot />
		</label>
		<input
			:id="id"
			:accept="accept"
			@change="updateValue"
			type="file"
			ref="inputRef"
			style="display: none"
		/>
		<div
			:class="`relative rounded-lg border-ant-primary transition-all duration-100 overflow-hidden ${!(file || defaultFile) ? 'h-0 my-0 border-0' : 'my-3 h-full border'}`"
		>
			<a target="_blank" class="px-4 py-2 flex" :href="filePreviewUrl">
				<FilePdfTwoTone
					style="font-size: 3rem"
					class="mr-1"
					two-tone-color="red"
				/>
				<div class="file-info flex flex-col gap-1 font-bold">
					<div class="name">{{ fileName }}</div>
					<div class="size flex-grow">{{ fileSize }}</div>
				</div>
			</a>
			<div class="absolute right-1.5 top-1.5">
				<button
					type="button"
					@click="removeSelectedFile"
					class="bg-red-50 transition-all duration-100 text-red-600 border rounded-full w-6 text-md p-1 border-red-600 leading-none hover:bg-red-100 hover:border-red-700 active:bg-red-200 active:border-red-800 active:text-red-700"
				>
					X
				</button>
			</div>
		</div>
	</div>
</template>

<script setup lang="ts">
import { FilePdfTwoTone } from '@ant-design/icons-vue';
import { ref, watch } from 'vue';
import { error } from 'toastr';

const props = defineProps<{
	defaultFile?: { url: string; name: string; size: string };
	value: object | null;
	id: string;
	accept?: string;
}>();

const emit = defineEmits<{
	(e: 'update:value', message: object | null): void;
}>();

const fileName = ref(props.defaultFile?.name || '');
const fileSize = ref(props.defaultFile?.size || '');
const filePreviewUrl = ref(props.defaultFile?.url || '');
const file = ref<File | null>(null);
const inputRef = ref();
const updateValue = (e: Event) => {
	file.value = (e.target as HTMLInputElement).files!.item(0);
	if (file.value) {
		if (file.value.size > 10 * 1024 * 1024) {
			error('10MBdan kichikroq fayl yuklang');
			file.value = null;
			return;
		}
	}
};

const removeSelectedFile = () => {
	file.value = null;
	inputRef.value.value = '';
};

const parseFileSize = (size: number, unit: number = 0): string => {
	const units = ['KB', 'MB', 'GB', 'TB'];
	const newSize = size / 1024;

	if (newSize >= 1000) {
		return parseFileSize(newSize, unit + 1);
	}

	const fileSize = Math.round(newSize * 100) / 100;

	return [fileSize, units[unit]].join(' ');
};

watch(file, (newValue) => {
	const defaultValue = props.defaultFile;
	fileName.value = newValue?.name || defaultValue?.name || '';
	fileSize.value = newValue?.size
		? parseFileSize(newValue.size)
		: defaultValue?.size || '';
	filePreviewUrl.value = newValue
		? URL.createObjectURL(newValue)
		: defaultValue?.url || '';

	emit('update:value', newValue);
});

defineExpose({ removeSelectedFile });
</script>
