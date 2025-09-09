<template>
    <div>
        <label>{{label?label:'Тип двигателя'}}</label>
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
            axios.get('/api/services/html/select/motortypes')
            .then(res => {
                this.data = res.data.data
            })
            .catch(error => {

            })
        }
    },
    props: ['name', 'value','label'],
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
