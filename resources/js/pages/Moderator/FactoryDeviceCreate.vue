<template>
	<a-form
		:model="form"
		:rules="rules"
		autocomplete="off"
		@finish="onFinish"
		@finishFailed="onFinishFailed"
		layout="vertical"
	>
		<a-space class="site-input-group-wrapper w-full" direction="vertical">
			<a-form-item>
				<a-button
					type="primary"
					class="bg-ant-primary"
					html-type="submit"
					>Saqlash
				</a-button>
			</a-form-item>
		</a-space>
	</a-form>
</template>
<script setup lang="ts">
import store from '@/store';
import { ref } from 'vue';

const form = ref();
const rules = ref([]);

const init = () => {
	store.commit('spinner/toggleSpinning', 'drawer');
};

const onFinish = () => {};
const onFinishFailed = () => {};

defineExpose({ init });
</script>
