<template>
    <select :name="name" class="form-control" multiple="multiple" v-model="selected">
        <option
            v-for="item in data"
            :value="item.id"
        >
            {{ item.name }}
        </option>
    </select>
</template>

<script>

export default {
    name: 'html-multi-select',

    props: {
        value: {
            type: Array,
            default: []
        },
        name: {
            type: String,
            default: null,
        },
        data: {
            type: Array,
            default: []
        },
        placeholder: {
            type: String,
            default: null
        }
    },
    data() {
        return {
            selected: this.value,
        }
    },
    watch: {
        selected(v){
            this.$emit('input', v);
        }
    },
}
</script>
