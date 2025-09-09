<template>
    <div>
        <label> {{title ? title : 'Модель'}}</label>
        <select v-model="selected" class="form-control">
            <option value="" selected >Укажите параметр</option>
            <option v-for="(item,i) in marks" :value="item.id" :key="'markselect'+i">
                {{item.prefix}} {{ item.name }}
            </option>
        </select>
    </div>
</template>

<script>
export default {
    data() {
        return {
            marks: [],
        }
    },

    mounted() {
        this.loadData(this.brand)
    },

    methods: {
        loadData(brand='') {
            var str = [];

            if(brand > 0)
                str.push('brand_id=' + brand)

            if(this.actual == 1)
                str.push('actual=1')

            if(this.nonactual == 1)
                str.push('nonactual=1')

            str = '?' +str.join('&')

            axios.get('/api/services/html/select/marks' + str)
            .then(res => {
                this.marks = res.data.data
            })
            .catch(error => {

            })
        }
    },
    props: ['name','value','brand','actual','nonactual','title'],

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

    watch: {
        brand(v){

            this.loadData(this.brand)
        }
    },
}
</script>
