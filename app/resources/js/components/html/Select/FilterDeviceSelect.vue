<template>
    <div>
        <label>Группа оборудования</label>
        <select v-model="selected" class="form-control">
            <option value="" selected >Укажите параметр</option>
            <option v-for="(item,i) in filters" :value="item.id" :key="'devicefilter'+i">
                {{ item.name }}
            </option>
        </select>
    </div>
</template>

<script>
export default {
    data() {
        return {
            filters: [],
        }
    },
    mounted() {
        this.loadFilters()
    },
    methods: {
        loadFilters() {
            axios.get('/api/services/html/select/devicefilters')
            .then(res => {
                this.filters = res.data.data
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
    // watch: {
    //     selected(v){
    //         this.$emit('input', v);
    //     }
    // },
}
</script>
