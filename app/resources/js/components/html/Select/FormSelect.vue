<template>
    <div>
        <label>{{label ? label : 'Форма'}}</label>
        <select v-model="selected" class="form-control">
            <option value="" selected >Укажите параметр</option>
            <option v-for="(item,i) in data" :value="item.id" :key="'form-select'+i">
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
            var param = '?';
            if(this.widget==1)
                param+='widget=1'
            axios.get('/api/services/html/select/forms'+param)
            .then(res => {
                this.data = res.data.data
            })
            .catch(error => {

            })
        }
    },
    props: ['name', 'value', 'label', 'widget'],
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
