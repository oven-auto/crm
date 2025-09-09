<template>
<div v-if="!loading && data.length" class="d-flex align-items-center">
        <div v-for="item in data" :key="'count-car'+item.delivery_type_id" style="" class="d-flex align-items-center">
            <div class=" ml-1" style="width:24px;height:24px;" v-tooltip:top="item.name">
                <div class="indicator-wrapper">
                <ion-icon
                    class=""
                    name="car-sport-outline"
                    :class="getCssClass(item.delivery_type_id)"
                >
                </ion-icon>
                <span class="indicator-count" :class="getCssCount(item.delivery_type_id)">{{item.count}}</span>
                </div>
            </div>
        </div>
</div>
</template>

<script>
export default {
    name: 'CarsComplectCount',
    data() {
        return {
            data: {},
            loading: true
        }
    },
    computed: {
        complectParamStr() {
            if(this.complectation_id)
                return 'complectation_id='+this.complectation_id;
        }
    },
    methods: {
        getCssClass(id) {
            switch(id) {
                case 1:
                    return 'blue-indicator'
                    break
                case 2:
                    return 'yellow-indicator'
                    break
                case 3:
                    return 'green-indicator'
                    break
                case 4:
                    return 'red-indicator'
                    break
                default:
                    return ''
            }
        },

        getCssCount(id) {
            switch(id) {
                case 1:
                    return 'blue-count'
                    break
                case 2:
                    return 'yellow-count'
                    break
                case 3:
                    return 'green-count'
                    break
                case 4:
                    return 'red-count'
                    break
                default:
                    return ''
            }
        },

        loadData() {
            this.loading = true
            axios.get('/api/services/count/cars?'+this.complectParamStr)
            .then( res => {
                this.data = res.data.data
            }).catch( errors => {
                console.log(errors)
            }).finally( () => {
                this.loading = false
            })
        }
    },
    props: ['complectation_id'],
    mounted() {
        this.loadData()
    }
}
</script>

<style scoped>

</style>
