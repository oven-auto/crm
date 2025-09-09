<template>
<div>
    <div class="pb-3 mb-3 h5">
        Доподнительное оборудование: {{formatPrice(devicePrice)}}
    </div>
    <div class="row pb-5 border-bottom">
        <div class="col-6" v-for="chunk in chunkArray(devices, Math.ceil(devices.length/2))">
            <div v-for="itemDevice in chunk">
                <label class="checkbox d-flex align-items-center" :title="itemDevice.name">
                    <input class="device-checkbox-toggle" type="checkbox" v-bind:value="itemDevice.id" v-model="install" v-on:change="returnData" >
                    <div class="checkbox__text" style="">
                        {{itemDevice.name}}
                    </div>
                </label>
            </div>
        </div>
    </div>
    <div class=" row">
        <div class="col">
            <Vinput v-model="installPriceComputed" v-on:input="returnData" :label="'Цена установки ДО'"></Vinput>
        </div>

        <div class="col">
            <Vinput v-model="costPriceComputed" v-on:input="returnData" :label="'Себестоимость ДО'"></Vinput>
        </div>
    </div>
</div>
</template>

<script>
import Vinput from '../html/TextInput';

export default {
    components: {Vinput},
    name: 'car-device',
    data() {
        return {
            devices: [],
            install: [],
            price: 0,
            cost: 0,
        }
    },
    mounted() {
        this.loadData()
        this.price = this.devicePrice
        this.install = this.installProp
        this.cost = this.costPrice
    },
    computed: {

        installPriceComputed: {
            get() {
                var res = parseInt(this.price)
                if(isNumeric(res))
                    return res;
                return 0;
            },
            set(val) {
                this.price = val;
            },
        },

        costPriceComputed: {
            get() {
                var res = parseInt(this.cost)
                if(isNumeric(res))
                    return res;
                return 0;
            },
            set(val) {
                this.cost = val;
            },
        }
    },
    props: ['installProp', 'devicePrice', 'costPrice'],

    methods: {
        returnData() {
            this.$emit('updateDevice', {
                devices: this.install,
                price: isNumeric(parseInt(this.price)) ?  parseInt(this.price) : 0,
                cost: isNumeric(parseInt(this.cost)) ?  parseInt(this.cost) : 0,
            })
        },

        formatPrice(param) {
            return number_format(param,0,'',' ', 'руб.');
        },

        chunkArray(arr, chunk) {
            var i, j, tmp = [];
            for (i = 0, j = arr.length; i < j; i += chunk) {
                tmp.push(arr.slice(i, i + chunk));
            }
            return tmp;
        },

        loadData() {
            axios.get('/api/devices?dops=1')
            .then(res => {
                this.devices = res.data.data
            })
            .catch(errors => {
                console.log(errors)
            })
        },
    },
    watch: {
        installProp(v) {
            this.price = this.devicePrice
            this.install = this.installProp
            this.cost = this.costPrice
        },
        devicePrice(v) {
            this.price = this.devicePrice
            this.install = this.installProp
            this.cost = this.costPrice
        }
    }
}
</script>
