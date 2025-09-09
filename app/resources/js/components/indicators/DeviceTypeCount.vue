<template>
<span>
    <span v-if="loading == false && count!=0">
        <div class="indicator-wrapper ml-1" v-tooltip:top="'Кол-во оборудования данного типа'">
            <ion-icon
                class="red-indicator"
                name="bag-check-outline"
            >
            </ion-icon>
            <span class="indicator-count red-count">{{count}}</span>
        </div>
    </span>
    <span v-else-if="loading == false && count==0">
        <div class=" ml-1 indicator-wrapper">
            <ion-icon name="close-outline" @click="deleteType()"></ion-icon>
        </div>
    </span>
</span>
</template>

<script>
export default {
    name: 'device-type-count',
    data() {
        return {
            count: '',
            loading: true
        }
    },
    props: ['device_type_id', 'index'],
    methods: {
        loadData() {
            this.loading = true
            axios.get('/api/services/count/devicetypes?'+this.paramStr)
            .then(res => {
                this.count = res.data.data
            }).catch(errors => {

            }).finally(()=>{
                this.loading = false
            })
        },
        deleteType() {
            var status = confirm("Удалить данный тип оборудования?")
            if(status)
                axios.delete('/api/devicetypes/'+this.device_type_id)
                .then(res => {
                    this.$emit('deleteTrigger', {index: this.index, message: res.data.message})
                }).catch(errors => {

                }).finally(() => {

                })
        }
    },
    computed: {
        paramStr() {
            if(this.device_type_id)
                return 'device_type_id='+this.device_type_id;
        }
    },
    mounted() {
        this.loadData()
    }
}
</script>
