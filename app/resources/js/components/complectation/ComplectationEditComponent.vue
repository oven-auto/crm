<template>
<div class="device-type-edit">

    <spin v-if="loading && urlId"></spin>

    <error v-if="notFound"></error>

    <div v-else>
        <form>
            <div class="h5">Комплектация: {{ complectation.name ? complectation.name : 'новая' }}</div>

            <div class="row">
                <div class="col text-right">
                    <label class="checkbox " :title="'Статус'">
                        <input class="device-checkbox-toggle" type="checkbox" v-bind:value="complectation.status" v-model="complectation.status">
                        <div class="checkbox__text" style="">
                            <div style="width: 200px;text-align: left;">
                                {{ (complectation.status) ? 'Комплектация включена' : 'Комплектация выключена' }}
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="row pb-3">
                <div class="col-6">
                    <div >
                        <label for="name">Название</label>
                        <input type="text" name="name" v-model="complectation.name" class="form-control"/>
                    </div>

                    <div >
                        <label for="name">Код</label>
                        <input type="text" name="name" v-model="complectation.code" class="form-control"/>
                    </div>

                    <div class="row">
                        <div class="col">
                            <label for="name">Цена</label>
                            <input type="text" name="name" v-model="complectation.price" class="form-control"/>
                        </div>

                        <div class="col">
                            <label for="name" class="d-block">&nbsp</label>
                            <label class="checkbox m-0 mt-1" :title="'Статус цены'">
                                <input class="device-checkbox-toggle" type="checkbox"
                                    v-bind:value="complectation.price_status" v-model="complectation.price_status">
                                <div class="checkbox__text" style="">
                                    <div>
                                        Переоценка
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="row">
                        <div class="col-6">
                            <BrandSelect v-model="complectation.brand_id" name="brand_id"></BrandSelect>
                        </div>
                        <div class="col-6">
                            <MarkSelect v-model="complectation.mark_id" :brand="complectation.brand_id" name="mark_id"></MarkSelect>
                        </div>
                    </div>

                    <MotorSelect v-model="complectation.motor_id" :brand="complectation.brand_id" name="motor_id"></MotorSelect>

                    <ComplectationSelect
                        v-model="complectation.parent_id"
                        :mark="complectation.mark_id"
                        :label="'Родительская комплектация'"
                        @changeParent="loadParentData"
                    ></ComplectationSelect>

                </div>
            </div>

            <div class=" py-3">
                <DeviceGroupCheckbox
                    :install="complectation.devices"
                    @checkDevice="setDivices"
                    :brand="complectation.brand_id"
                >
                </DeviceGroupCheckbox>
            </div>

            <div class="row py-3" v-if="packs">
                <div class="col-12 h5 pb-3">
                    Опции
                </div>
                <div class="col-12">
                    <table class="table complectation-table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <td>Код</td>
                                <td>Название</td>
                                <td>Цена</td>
                                <td>Тип</td>
                                <td>Оборудование</td>
                            </tr>
                        </thead>

                        <tbody>
                        <tr v-for="pack in packs">
                            <td class="pl-1">
                                <label class="checkbox d-flex align-items-center" :title="pack.name">
                                    <input
                                        class="device-checkbox-toggle "
                                        type="checkbox"
                                        v-bind:value="pack.id"
                                        v-model="complectation.packs"
                                        v-on:change="appendColoredOption(pack)"
                                    >
                                    <div class="checkbox__text" style="">
                                        {{pack.code}}
                                    </div>
                                </label>
                            </td>
                            <td>{{pack.name}}</td>
                            <td>{{pack.price}}</td>
                            <td>{{pack.colored ? 'Цветовой' : ''}}</td>
                            <td>
                                <span v-for="device in pack.devices" class="badge badge-secondary mx-1">
                                    {{device.name}}
                                </span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row py-3" v-if="complectation.colors">
                <div class="col-3 item-complect-color mb-3" v-for="markcolors in complectation.colors">
                    <div class="text-center rounded border p-2 flex-column d-flex" style="height: 100%;">
                        <div>
                            <label class="checkbox d-flex align-items-center" :title="markcolors.color.code">
                                <input
                                    class="item-color-input device-checkbox-toggle"
                                    type="checkbox"
                                    v-bind:value="markcolors.id"
                                    v-model="complectation.install_colors"
                                    @change="ClearColorPack(markcolors)"
                                >
                                <div class="checkbox__text" style="">
                                    {{markcolors.color.code}}
                                </div>
                            </label>
                        </div>
                        <div>
                            <img :src="markcolors.image" style="width:100%">
                        </div>
                        <div class="text-muted">{{markcolors.color.name}}</div>
                        <div>
                            <div v-for="coloredPack in coloredOptions">
                                <label class="checkbox d-flex align-items-center" :title="coloredPack.code">
                                    <input
                                        class="device-checkbox-toggle color-pack"
                                        type="checkbox"
                                        v-bind:value="coloredPack.id"
                                        :color-id="markcolors.id"

                                        v-model="markcolors.color_pack"
                                    >
                                    <div class="checkbox__text" style="">
                                        {{coloredPack.code}}
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>


        <FormControll :id="urlId"></FormControll>

    </div>
</div>
</template>

<script>
import Error from '../alert/ErrorComponent';
import Message from '../alert/MessageComponent';
import Spin from '../spinner/SpinComponent';

import BrandSelect from '../html/BrandSelect';
import MarkSelect from '../html/MarkSelect';
import MotorSelect from '../html/MotorSelect';
import ComplectationSelect from '../html/ComplectationSelect';
import DeviceGroupCheckbox from '../checkbox/DeviceGroupCheckBox'


export default {
    name: 'complectation-edit',
    components: {
        Error, Message, Spin, BrandSelect, MarkSelect, MotorSelect, ComplectationSelect,DeviceGroupCheckbox
    },
    data() {
        return {
            complectation: {
                id: '',
                name: '',
                code: '',
                price: '',
                brand_id: 0,
                mark_id: 0,
                motor_id: 0,
                sort: 0,
                status: 0,
                parent_id: 0,
                devices: [],
                packs: [],
                colors: [],
                colorPack: [],
                price_status: 1,
                install_colors: [],
            },

            packs: [],
            markcolors: [],
            //coloredOptions: {},
            notFound: false,
            loading: true,
            urlId: this.$route.params.id,
            succes: false,
            message: null,
            firstLoad: false,
        }
    },
    mounted() {
        if(this.urlId)
        {
            this.firstLoad = true
            this.loadData(this.urlId)

        }
    },

    computed: {
        coloredOptions() {
            var arr = {}
            this.packs.forEach( ( item ) => {
                if(item.colored && this.complectation.packs.includes(item.id))
                    arr[item.id] = item;
            })
            return arr
        }

    },

    methods: {
        ClearColorPack(obj) {
            if(!this.complectation.install_colors.includes(obj.id))
                obj.color_pack = []
        },

        setDivices(data) {
            this.complectation.devices = data.devices
        },

        appendColoredOption(pack){
            var status = event.target.checked
            if(pack.colored) {
                if(status) {
                    this.coloredOptions[pack.id] = {
                        id: pack.id,
                        code: pack.code,
                    }
                } else {
                    delete this.coloredOptions[pack.id]
                    this.complectation.colors.forEach( (color, a) => {
                        color.installColorPack.forEach( (packId, i) => {
                            if(packId == pack.id)
                            {
                                delete color.installColorPack[i]
                            }
                        })
                    })
                }
            }
        },

        getPacks() {
            var param = [];

            if(this.complectation.mark_id)
                param.push('mark_id=' + this.complectation.mark_id)

            axios.get('/api/packs?' + param.join('&'))
            .then(res => {
                this.packs = res.data.data
            })
            .catch(errors => {

            })
        },

        getColors() {
            var param = 'mark_id=' + this.complectation.mark_id
            axios.get('/api/services/html/color/mark?' + param)
            .then( (res) => {
                this.complectation.colors = res.data.data
            })
            .catch( (errors) => {

            })
        },

        loadData(id) {
            edit(this, '/api/complectations/' + this.urlId + '/edit', 'complectation', 'message')
        },

        updateData(id) {
            update(this, '/api/complectations/' + this.urlId, this.getFormData('patch'), 'complectation', 'message' )
        },

        storeData() {
            storage(this, '/api/complectations/', this.getFormData(), 'complectation', 'message', 'urlId', 'complectations')
        },

        getFormData(method = '') {
            var formData = new FormData();
            formData.append('name',                     this.complectation.name)
            formData.append('code',                     this.complectation.code)
            formData.append('price',                    this.complectation.price)
            formData.append('brand_id',                 this.complectation.brand_id)
            formData.append('mark_id',                  this.complectation.mark_id)
            formData.append('motor_id',                 this.complectation.motor_id)
            formData.append('status',                   this.complectation.status)
            formData.append('parent_id',                this.complectation.parent_id)
            formData.append('price_status',             Number(this.complectation.price_status))

            this.complectation.devices.forEach(function(item) {
                formData.append('devices[]', item)
            })

            this.complectation.packs.forEach(function(item){
                formData.append('packs[]', item)
            })

            this.complectation.install_colors.forEach(function(item){
                formData.append('install_colors[]', item)
            })

            this.complectation.colors.forEach(color => {
                if(this.complectation.install_colors.includes(color.id) && color.color_pack.length)
                    color.color_pack.forEach(itemPackId => {
                        if(this.complectation.packs.includes(itemPackId))
                            formData.append('color_pack['+color.id+'][]', itemPackId)
                    })
            })

            if(method == 'patch')
                formData.append("_method", "PATCH");

            return formData
        },

        loadParentData(data) {
            this.loading = true
            axios.get('/api/complectations/' + this.complectation.parent_id + '/edit')
            .then( response => {
                this.complectation.brand_id = response.data.data.brand_id
                this.complectation.mark_id = response.data.data.mark_id
                this.complectation.motor_id = response.data.data.motor_id
                this.complectation.devices = response.data.data.devices
                this.complectation.packs = response.data.data.packs
                this.complectation.install_colors = response.data.data.install_colors
                this.complectation.color_pack = response.data.data.color_pack
            }).catch(errors => {
                makeToast(this,'Родительская комплектация не прогружена. Подробнее ошибка в консоли F12')
                console.log(errors)
            }).finally(()=>{
                this.loading = false;
                makeToast(this,'Родительская комплектация прогружена')
            })
        },

    },

    watch: {
        'complectation.brand_id': {
            immediate: true,
            handler() {
                this.markcolors = []
                if(!this.firstLoad)
                    this.complectation.mark_id = 0
            },
        },
        'complectation.mark_id': {
            immediate: true,
            handler() {
                if(!this.firstLoad)
                    this.getColors();
                    this.getPacks();
            },
        },

    }

}
</script>

<style scoped>
.complectation-table .badge{
    white-space: normal;
}
</style>
