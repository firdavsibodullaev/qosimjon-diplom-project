<template>
    <a-spin :spinning="spinning" :delay="delayTime">
        <slot/>
    </a-spin>
</template>
<script>
export default {
    name: "Spinner",
    props: {
        spinning: {
            type: Boolean,
            required: true,
            default: false
        }
    },
    data() {
        return {
            delayTime: 100
        };
    }
}
</script>
