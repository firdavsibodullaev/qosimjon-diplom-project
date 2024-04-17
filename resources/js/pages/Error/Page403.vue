<template>
	<div class="h-[100vh] w-[100vw] flex justify-center">
		<div class="mt-16">
			<p class="text-center text-9xl">403</p>
			<p class="text-center text-5xl mb-5">Ruxsat yo'q</p>
			<p class="text-center text-2xl text-gray-600">
				<router-link
					:to="{ name: 'index' }"
					class="text-blue-800 transition duration-200 hover:text-blue-600"
				>
					Bosh sahifaga
				</router-link>
				qaytish
			</p>
		</div>
	</div>
</template>
<script setup></script>
