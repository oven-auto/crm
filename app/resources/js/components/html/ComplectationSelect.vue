<template>
    <div>
        <label>{{getLabel}}</label>
        <select v-model="selected" class="form-control" @change="changeComplectation">
            <option value="" selected >Укажите параметр</option>
            <option v-for="item in complectations" :value="item.id" :key="'complectation-select'+item.id">
                {{ item.code }}
                {{ item.name }}
                {{ item.motor.size}} ( {{ item.motor.power }} л.с.)
                {{ item.motor.transmission.acronym }}
                {{ item.motor.driver.acronym }}
            </option>
        </select>
    </div>
</template>

<script>
export default {
    data() {
        return {
            complectations: [],
        }
    },

    mounted() {
        this.loadData(this.mark)
    },

    methods: {
        changeComplectation() {
            this.$emit('changeParent', {
                //id: this.selected
            })
        },

        loadData(mark='') {
            var str = '';
            if(mark > 0)
                str = '?mark_id=' + mark

            axios.get('/api/services/html/select/complectations' + str)
            .then(res => {
                this.complectations = res.data.data
            })
            .catch(error => {

            })
        }
    },
    props: ['name','value','mark','label'],
    computed: {
        getLabel() {
            return this.label ?  this.label : 'Комплектация'
        },
        selected: {
            get() {
                return this.value;
            },
            set(val) {
                if(!val)
                    val = 0
                this.$emit('input', val);
            },
        },
    },

    watch: {
        mark(v){
            this.loadData(this.mark)
        }
    },
}
</script>
