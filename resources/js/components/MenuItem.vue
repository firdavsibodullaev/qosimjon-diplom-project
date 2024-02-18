<template>
    <a-menu-item :key="key">
        <slot/>
    </a-menu-item>
</template>
<script>
export default {
    name: 'MenuItem',
    props: {
        key: {
            type: String,
            required: true
        }
    }
}
</script>
