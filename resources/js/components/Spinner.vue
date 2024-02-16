<template>
    <a-spin :spinning="$store.getters['spinner/getSpinning']" :delay="delayTime">
        <slot/>
    </a-spin>
</template>
<script>
export default {
    name: "Spinner",
    data() {
        return {
            delayTime: 10
        };
    }
}
</script>
