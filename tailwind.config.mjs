/** @type {import('tailwindcss').Config} */
export default {
	content: [
		'./resources/**/*.blade.php',
		'./resources/**/*.ts',
		'./resources/**/*.js',
		'./resources/**/*.vue',
	],
	theme: {
		extend: {
			textColor: {
				'ant-primary': '#1677ff',
			},
			colors: {
				'ant-primary': '#1677ff',
			},
		},
	},
	plugins: [],
};
