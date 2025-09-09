<template>
<div>
    <div><label>Модели</label></div>
    <div v-if="marks.length">
        <div v-for="item in marks">
            <label class="checkbox " :title="'Статус'">
                <input class="device-checkbox-toggle" type="checkbox" v-bind:value="item.id" v-model="install" v-on:change="change">
                <div class="checkbox__text" style="">
                    <div style="width: 200px;text-align: left;">
                        <span v-if="item.status">
                            {{ item.brand.name }}
                            {{ item.prefix }}
                            {{ (item.name) }}
                        </span>
                        <span v-else class="text-danger">
                            {{ item.brand.name }}
                            {{ item.prefix }}
                            {{ (item.name) }}
                            (модель не актуальна)
                        </span>

                    </div>
                </div>
            </label>
        </div>
    </div>
    <div v-else>
        Модели не найдены
    </div>
</div>
</template>

<script>
export default {
    name: 'credit-marks',
    props: ['brand','value'],
    data() {
        return {
            marks: [],
            install: []
        }
    },
    computed: {

    },
    methods: {
        change() {

        },
        loadData() {
            this.marks = []
            axios.get('/api/marks?brand_id=' + this.brand)
            .then( (response) => {
                this.marks = response.data.data
                this.install = this.value
            })
            .catch( (errors) => {

            })
            .finally( () => {

            })
        }
    },
    watch: {
        brand(v) {
            this.loadData()
        },
        install(v){
            this.$emit('onChange', this.install)
        }
    }
}
</script>
