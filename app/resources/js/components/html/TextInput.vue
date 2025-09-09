<template>
<div>
    <label>{{label}}</label>
    <input type="text" class="form-control" v-model="result">
</div>
</template>

<script>
export default {
    name: 'text-input',
    data() {
        return {
            data: '',
        }
    },
    props: ['label', 'value'],
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
