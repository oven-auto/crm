<template>
<div>
    <label>{{label}}: </label>
    <input type="date" class="form-control" v-model="result" >
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
