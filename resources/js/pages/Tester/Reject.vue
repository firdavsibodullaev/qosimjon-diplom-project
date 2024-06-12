<template>
	<a-form :model="form" @finish="reject" layout="vertical">
		<a-form-item
			name="comment"
			:rules="[
				{ required: true, message: 'Rad etish sababini kiriting' },
			]"
			label="Rad etish sababi"
		>
			<a-textarea
				:maxlength="1000"
				v-model:value="form.comment"
				:rows="7"
				placeholder="Rad etish sababini kiriting"
			/>
			<span class="float-right">{{ 1000 - form.comment.length }}</span>
		</a-form-item>
		<a-form-item
			name="document"
			label="Hujjat"
			:rules="[{ required: true, message: 'Hujjat yuklang' }]"
			extra="PDF hujjat yuklang, 10MBdan ortiq bo'lmasin"
		>
			<upload-file
				ref="fileUpload"
				id="document"
				:required="true"
				accept="application/pdf"
				v-model:value="form.document"
			>
				<template #icon><UploadOutlined class="mr-2" /></template>
				Hujjatni yuklang
			</upload-file>
		</a-form-item>
		<a-button class="mt-5" block html-type="submit" type="primary"
			>Rad etish
		</a-button>
	</a-form>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import UploadFile from '@/components/UploadFile.vue';
import { UploadOutlined } from '@ant-design/icons-vue';

const props = defineProps<{
	id: number;
}>();
const form = ref({
	comment: '',
	document: null,
});

const isDisabled = ref(false);
const emits = defineEmits(['reject']);

const reject = (value: any) => {
	emits('reject', value);
};
</script>
<style scoped></style>
