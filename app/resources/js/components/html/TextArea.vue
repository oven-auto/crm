<template>
<div style="" class="">
        <label v-if="labelempty != 1">{{label}}</label>

        <textarea v-if="css" :class="css" v-model="result" style="" :placeholder="placeholder"></textarea>
        <textarea v-else class="form-control" v-model="result" style="" :placeholder="placeholder"></textarea>
</div>
</template>

<script>
export default {
    name: 'text-area',
    data() {
        return {
            data: '',
        }
    },
    props: ['label', 'value','labelempty','placeholder','css'],
    computed: {
        result: {
            get() {
                console.log(this.value)
                return this.value;
            },
            set(val) {
                this.$emit('input', val);
            },
        }
    }
}
</script>
