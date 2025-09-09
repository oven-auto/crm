<template>
<div class="">
    <div class="row">
        <div class="col-12 h5 pb-3">
            Оборудование
        </div>
    </div>

    <div v-for="(typeGroup,g) in devices" :key="'group'+g" class="row" style="width: 100%;">
        <div class="col-4" v-for="(chunk,k) in chunkArray(typeGroup, Math.ceil(typeGroup.length/3))" :key="'chunk'+k">
            <div v-for="(itemDevice,i) in chunk" :key="'chunk-device'+i" >
                <div v-if="i == 0">
                    <div class="p-1 mb-1" style="background: #eee;">{{itemDevice.type.name}}</div>
                </div>
                <div v-else-if="i != 0 && chunk[i].type.name != chunk[i-1].type.name">
                    <div class="p-1 mb-1" style="background: #eee ;">{{itemDevice.type.name}}</div>
                </div>

                <div class="row pb-1">
                    <div class="col-11">
                        <label class="checkbox d-flex align-items-center mb-0"
                            :title="itemDevice.name"
                            :class="{'active-input':itemDevice.checked, 'preinstall-input':selected.indexOf(itemDevice.id)>=0}"
                        >
                            <input
                                class="device-checkbox-toggle"
                                type="checkbox"
                                v-bind:value="itemDevice.id"
                                v-model="selected"
                                @change="changeDevice"
                                style="overflow:hidden;"
                            >
                            <div class="checkbox__text" style="overflow:hidden" >
                                {{itemDevice.name}}
                            </div>
                        </label>
                    </div>
                    <div class="col-1 p-0 d-flex align-items-center">
                        <input type="checkbox" v-model="itemDevice.checked" class="button-check" >
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>

export default {
    name: 'device-group-check-box',
    props: ['brand', 'install'],
    data() {
        return {
            devices: [],
            selected: [],
        }
    },

    computed: {
    },

    methods: {


        changeDevice() {
            this.$emit('checkDevice', {
                devices: this.selected
            })
        },
        chunkArray(arr, chunk) {
            var i, j, tmp = [];
            for (i = 0, j = arr.length; i < j; i += chunk) {
                tmp.push(arr.slice(i, i + chunk));
            }
            return tmp;
        },
        getDevices() {
            var param = 'brand_id='+this.brand+'&group=type'
            axios.get('/api/services/html/select/groupdevices?' + param)
            .then(res => {
                for(var i in res.data.data)
                    for(var k in res.data.data[i])
                        res.data.data[i][k].checked = 0
                this.devices = res.data.data
                this.selected = this.install


            })
            .catch(errors => {

            })
        },
    },
    watch: {
        brand: function(val) {
            this.getDevices()
        },
        install: function(val) {
            this.selected = this.install
        }
    }
}
</script>

<style>
.button-check{
    border-radius: 100%;
    background: #ececec;
    width: 20px;
    height: 20px;
    display: inline-block;
    /* margin-top: 8px;*/
    margin-left: -5px;
    cursor:crosshair;
}
.button-check:hover{
    background: #bdbdbd;
}
.active-input{
    background-color: #f9ea8f;
    background-image: linear-gradient(315deg, #f9ea8f 0%, #aff1da 74%);
    color: #666 !important;
}
.preinstall-input{
    color: #bdbdbd;
}
</style>
