<template>
    <div class="row py-3">
        <div class="col-12 h5 mb-3">
            Характеристики модели
        </div>
        <div class="col-3" v-for="item in res">
            <label>{{ item.name }} </label>
            <input type="text" class="form-control" v-model="item.value">
        </div>
    </div>
</template>

<script>
export default {
    name: 'MarkProperties',

    props: {
        installed: []
    },

    data() {
        return {
            properties: []
        }
    },

    computed: {
        res: function() {
            this.properties.forEach( (item) => {
                this.installed.forEach( (instalItem) => {
                    if(item.id == instalItem.id)
                        item.value = instalItem.value
                })
            })
            return this.properties
        }
    },

    mounted() {
        this.loadProperties();
    },

    methods: {
        loadProperties() {
            this.loading = true;
            axios.get('/api/properties')
            .then(res => {
                this.properties = res.data.data
                this.properties.forEach( (item) => {
                    item.value = ''
                })
            })
            .catch(errors => {
                console.log(errors)
            })
            .finally(() => {
                this.loading = false;
            })
        },
    },

    watch: {
        // Эта функция запускается при любом изменении значений
        // в вычисляемом свойстве `salary`.
        res(newValue, oldValue) {
            // Пробрасываем данные родительскому компоненту,
            // ч/з вызов метода.
            this.$emit('updateProperties', this.properties)
        }
    }

}
</script>
