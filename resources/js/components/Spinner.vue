<template>
    <a-spin :spinning="$store.getters[`spinner/get${ucFirst(type)}Spinning`]"
            :delay="delayTime">
        <slot/>
    </a-spin>
</template>
<script>
export default {
    name: "Spinner",
    props: {
        type: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            delayTime: 0
        };
    },
    methods: {
        ucFirst(string) {
            return string.charAt(0).toUpperCase() + string.slice(1)
        }
    }
}
</script>
