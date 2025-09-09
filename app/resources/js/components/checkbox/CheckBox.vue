<template>
<div>
    <label class="checkbox m-0" :title="label">
        <input class="device-checkbox-toggle" type="checkbox" v-bind:value="result" v-model="result">
        <div class="checkbox__text" style="">
            <div>
                {{label}}
            </div>
        </div>
    </label>
</div>
</template>

<script>

export default {
    name: 'check-box-input',
    data() {
        return {
            data: '',
        }
    },
    props: ['label', 'value'],
    computed: {
        result: {
            get() {
                return this.value;
            },
            set(val) {
                this.$emit('input', val);
            },
        }
    }
}

</script>
