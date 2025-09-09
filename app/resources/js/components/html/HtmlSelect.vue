<template>
    <select v-model="selected" class="form-control">
        <option value="" selected >Укажите параметр</option>
        <option v-for="item in options" :value="item.id">
            {{ item.name }}
        </option>
    </select>
</template>

<script>

export default {
    name: 'html-select',

    props: {
        options: {
            type: Array,
            default: []
        },
        value: {
            type: Number,
            default: 0
        }
    },
    computed: {
        selected: {
            get() {
                return this.value;
            },
            set(val) {
                this.$emit('input', val);
            },
        },
    },
}
</script>
