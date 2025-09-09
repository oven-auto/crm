<template>
    <div>
        <label>Класс токсичности</label>
        <select v-model="selected" class="form-control">
            <option value="" selected >Укажите параметр</option>
            <option v-for="(item,id) in data" :value="id">
                {{ item }}
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
            axios.get('/api/services/html/select/toxic')
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
                if(this.value == null)
                    return ''
                return this.value;
            },
            set(val) {
                var res = val
                if(res == null)
                     res = ''
                this.$emit('input', res);
            },
        },
    },
}
</script>
