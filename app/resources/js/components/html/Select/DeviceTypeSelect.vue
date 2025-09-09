<template>
    <div>
        <label>{{label ? label : 'Категория оборудования'}}</label>
        <select v-model="selected" class="form-control">
            <option value="" selected >Укажите параметр</option>
            <option v-for="item in types" :value="item.id">
                {{ item.name }}
            </option>
        </select>
    </div>
</template>

<script>
export default {
    data() {
        return {
            types: [],
        }
    },
    mounted() {
        this.loadTypes()
    },
    methods: {
        loadTypes() {
            axios.get('/api/services/html/select/devicetypes')
            .then(res => {
                this.types = res.data.data
            })
            .catch(error => {

            })
        }
    },
    props: ['name', 'value', 'label'],
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
