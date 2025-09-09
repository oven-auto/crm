<template>
    <div>
        <label>Тип трансмиссии</label>
        <select v-model="selected" class="form-control">
            <option value="" selected >Укажите параметр</option>
            <option v-for="item in data" :value="item.id">
                {{ item.name }}
            </option>
        </select>
    </div>
</template>

<script>
export default {
    data() {
        return {
            data: [],
        }
    },
    mounted() {
        this.loadData()
    },
    methods: {
        loadData() {
            axios.get('/api/services/html/select/motortransmissions')
            .then(res => {
                this.data = res.data.data
            })
            .catch(error => {

            })
        }
    },
    props: ['name', 'value'],
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
