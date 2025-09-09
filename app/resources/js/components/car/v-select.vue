<template>
    <div v-if="!loading">
        <vOption v-model="selected" :options="array"></vOption>
    </div>
</template>

<script>

    import vOption from './v-option.vue'

    export default {
        name: 'SelectCustomWrapper',

        components: {vOption},

        mounted() {
            this.loadData()
        },

        props: ['value', 'url'],

        data() {
            return {
                array: [],
                message: '',
                loading: true,
            }
        },

        methods: {
            loadData() {
                this.loading = true
                axios.get(this.url)
                .then(res => {
                    this.array = res.data.data
                }).catch(err => {
                    this.message = errorsToStr(errors)
                }).finally(() => {
                    this.loading = false
                })
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
