<template>
    <div>
        <label>Бренд</label>
        <select v-model="selected" class="form-control">
            <option value="" selected >Укажите параметр</option>
            <option v-for="(item,i) in brands" :value="item.id" :key="'brandselect'+i">
                {{ item.name }}
            </option>
        </select>
    </div>
</template>

<script>
export default {
    data() {
        return {
            brands: [],
        }
    },
    mounted() {
        this.loadBrands()
    },
    methods: {
        loadBrands() {
            axios.get('/api/services/html/select/brands')
            .then(res => {
                this.brands = res.data.data
            })
            .catch(error => {

            })
        }
    },
    props: ['name','value'],
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
    // watch: {
    //     selected(v){
    //         this.$emit('input', v);
    //     }
    // },
}
</script>
