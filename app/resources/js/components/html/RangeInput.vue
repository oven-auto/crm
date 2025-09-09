<template>
<div>
    <label :title="title">{{label}}: {{result}}</label>
    <input type="range" class="form-control" v-model="result" :step="step" :min="min" :max="max">
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
    props: {
        label:{
           type: String,
           default: ''
        },
        value: {
            default: 0,
        },
        step: {
           type: Number,
           default: 1
        },
        min: {
            type: Number,
           default: 0
        },
        max: {
            type: Number,
           default: 100
        },
        title: {
            type: String,
            default: ''
        }
    },
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
