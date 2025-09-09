<template>
<div >
    <div class="h5">
        Опции
    </div>
    <div v-if="packs">
    <div v-for="item in packs" class="border-bottom py-2">

        <div class="row">
            <div class="col"><b class="">{{item.name}}</b></div>
            <div class="col text-right">{{priceFormat(item.price)}}</div>
        </div>
        <div>
            <span v-for="device in item.devices" class="text-muted">
                {{device.name}}
            </span>
        </div>
        <label class="checkbox d-flex align-items-center" :title="item.code" >
            <input class="device-checkbox-toggle" type="checkbox" v-bind:value="item.id" v-model="data" v-on:change="changePack" :disabled="item.colored == 1">
            <div class="checkbox__text" style="">
                {{item.code}}
            </div>
        </label>
    </div>
    </div>

    <div class="pt-3 text-right h5 mb-0">
        Итого цена опций: <span v-html="priceFormat(packPrice)"></span>
    </div>

</div>
</template>

<script>
export default {
    name: 'car-pack',
    data() {
        return {
            packs: [],
            data: [],
            colors:[],
        }
    },

    props: {
        complectation: {
            type: Number,
            default: 0
        },
        install: {
            type: Array,
            default: []
        },
        color: {
            type: Number,
            default: 0
        }
    },
    computed: {
        packPrice() {
            var sum = 0;

            this.packs.forEach( (item) => {
                if(this.data.includes(item.id))
                    sum += item.price
            })
            return sum
        }
    },

    mounted() {
        console.log(this.install)
        this.loadData()

    },

    methods: {
        priceFormat(param) {
            return number_format(param, 0, '', ' ', 'руб.');
        },

        changePack() {

            //this.checkColorPack()

            this.$emit('updatePack', {
                data: this.data,
                packPrice: this.packPrice
            })
        },

        loadData() {
            axios.get('/api/packs?complectation_id=' + this.complectation)
            .then((res) => {
                if(res.data.status == 1) {
                    this.packs = res.data.data
                    this.data = this.install
                    this.loadColor();
                    this.changePack()
                    //this.checkColorPack()
                    this.changePack()

                    console.log('---')
                    console.log(this.install)
                }
            })
            .catch((errors)=>{
                console.log(errors)
            })
        },

        loadColor() {
            if(this.complectation > 0)
                axios.get('/api/services/html/color/complectation?complectation_id=' + this.complectation)
                .then((res) => {
                        this.colors = res.data.data
                })
                .catch((errors)=>{

                })
        },

        checkColorPack() {
            this.packs.forEach( (item) => {
                if(item.colored) {
                    var index = this.data.indexOf(item.id)
                    if(index > -1)
                        this.data.splice(index, 1)
                }
            })

            this.colors.forEach( (item) => {
                if(this.color == item.id) {
                    if(!this.data.includes(item.pack_id))
                        this.data.push(item.pack_id)
                }
            })
        }
    },

    watch: {
        complectation(v) {
            console.log('complect')
            if(v>0){
                //this.data = []
                this.loadData();

            }
        },
        color(v) {
            console.log('color')
            if(v>0) {
                this.checkColorPack()
                //this.changePack()
            }
        },
        data(v) {
                this.$emit('updatePack', {
                data: this.data,
                packPrice: this.packPrice
            })
        }
    }

}
</script>
