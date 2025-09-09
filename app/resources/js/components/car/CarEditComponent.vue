<template>
    <div class="color-edit">

        <spin v-if="loading && urlId"></spin>

        <error v-if="notFound"></error>

        <div v-else>
            <form>
                <div class="h5">{{ car.vin ? car.vin : 'Новая автомобиль' }}: итоговая цена - {{formatPrice(fullPrice)}}</div>

                <div class="pb-3">
                    <span class="btn btn-secondary">Кузов: {{formatPrice(complectPrice)}}</span>

                    <span class="btn btn-secondary">Опции: {{formatPrice(packPrice)}}</span>

                    <span class="btn btn-secondary">Допы: {{formatPrice(car.device_price)}}</span>
                </div>

                <div class="row">
                    <div class="col">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#description">База</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#characteristics">Доп. оборудование</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#opinion">Клиент</a>
                            </li>
                        </ul>


                        <div class="tab-content pt-3">

                            <!--BEGIN CAR INFO-->
                            <div class="tab-pane fade show active" id="description">
                                <!-- <div class="row">
                                    <div class="col">
                                        <vSelect v-model="car.brand_id" :url="'/api/services/html/select/brands'"></vSelect>
                                    </div>

                                    <div class="col">
                                        <vSelect v-model="car.mark_id" :url="'/api/services/html/select/marks'"></vSelect>
                                    </div>
                                </div> -->

                                <div class="row">
                                    <div class="col"><BrandSelect name="'brand_id'" v-model="car.brand_id"></BrandSelect></div>
                                    <div class="col"><MarkSelect :actual="1"  v-model="car.mark_id" :brand="car.brand_id" name="mark_id"></MarkSelect></div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <ComplectationSelect v-model="car.complectation_id" :mark="car.mark_id" name="complectation_id"></ComplectationSelect>
                                    </div>

                                    <div class="col">
                                        <Tinput v-model="car.purchase" :label="'Закупка'"></Tinput>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col">
                                        <label for="name">VIN</label>
                                        <input type="text" name="name" v-model="car.vin" class="form-control"/>
                                    </div>
                                    <div class="col">
                                        <label for="name">Год выпуска</label>
                                        <select v-model="car.year" class="form-control">
                                            <option selected disabled>Укажите параметр</option>
                                            <option v-for="i in Array(20).fill().map((e, i) => i + 2010)" :value="i">
                                                {{i}}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <DeliveryType v-model="car.delivery_type_id" name="delivery_type_id"></DeliveryType>

                                        <DeliveryStageSelect v-model="car.delivery_stage_id" name="delivery_stage_id"></DeliveryStageSelect>
                                    </div>

                                    <div class="col-6">
                                        <MarkerSelect v-model="car.marker_id" name="marker_id"></MarkerSelect>

                                        <DateInput v-model="car.production_at" :label="'Дата производства'"></DateInput>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="p-3 rounded">
                                            <CarColor :complectation="car.complectation_id" @updateColor="onUpdateColor" :currentColorId="car.color_id"></CarColor>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="p-3 rounded">
                                            <CarPack
                                                :complectation="car.complectation_id"
                                                @updatePack="onUpdatePack"
                                                :install="car.packs"
                                                :color="car.color_id"
                                            >
                                            </CarPack>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--END CAR INFO-->


                            <!--BEGIN CAR OPTION-->
                            <div class="tab-pane fade" id="characteristics">
                                <CarDevice
                                    :installProp="car.devices"
                                    :devicePrice="car.device_price"
                                    :costPrice="car.device_cost"
                                    @updateDevice="onUpdateDevice"
                                >
                                </CarDevice>
                            </div>
                            <!--END CAR OPTION-->

                            <!--BEGIN CAR CLIENT-->
                            <div class="tab-pane fade" id="opinion">

                                <div class="row" v-if="hasClient">

                                    <div class="col">
                                        <div>{{car.client.lastname}} {{car.client.firstname}} {{car.client.fathername}}</div>
                                        <div>{{car.client.phone}}</div>
                                        <div>{{car.client.email}}</div>
                                    </div>

                                    <div class="col">
                                        <button type="button" class="btn btn-danger" @click="deleteClient()">
                                            Освободить автомобиль
                                        </button>
                                    </div>
                                </div>

                                <div class="row" v-else>
                                    <div class="col">
                                        <div class="h5">Автомобиль свободен</div>
                                        <div>
                                            <button class="btn btn-primary" type="button" @click="showModalClientFilter">Поиск клиента</button>
                                        </div>

                                        <div v-if="clients.clients" class="mt-3">
                                            <table class="table">
                                                <tr v-for="item in clients.clients">
                                                    <td>{{item.lastname}}</td>
                                                    <td>{{item.firstname}}</td>
                                                    <td>{{item.fathername}}</td>
                                                    <td>{{item.email}}</td>
                                                    <td>{{item.phone}}</td>
                                                    <td>
                                                        <span class="" @click="setClient(item)">
                                                            Выбрать
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div class="">
                                            <nav aria-label="Page navigation example">
                                                <ul class="pagination">
                                                    <li class="page-item"  v-bind:class="{active : (now_page == (index+1)) }" v-for="(item, index) in clients.last_page">
                                                        <span class="page-link " @click="cnangePage(index+1)" >
                                                        {{ (index+1) }}
                                                        </span>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--END CAR CLIENT-->

                        </div>
                    </div>
                </div>
            </form>

            <ClientSearchModal ref="client-filter-modal" @updateParent="getDataModal"></ClientSearchModal>

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
import ComplectationSelect from '../html/ComplectationSelect';

import CarColor from './CarColorComponent';
import CarPack from './CarPackComponent';
import CarDevice from './CarDeviceComponent';
import DeliveryType from '../html/Select/DeliveryTypeSelect';

import MarkerSelect from '../html/Select/MarkerSelect';
import DeliveryStageSelect from '../html/Select/DeliveryStageSelect';
import DateInput from '../html/DateInput'

import Tinput from '../html/TextInput';
import ClientSearchModal from '../modal/ClientFilterModal'

import vSelect from './v-select';

export default {
    name: 'car-edit',
    components: {
        Error,
        Message,
        Spin,
        BrandSelect, MarkSelect, ComplectationSelect,
        CarColor, CarPack, CarDevice,
        DeliveryType,MarkerSelect,DeliveryStageSelect,
        DateInput,
        ClientSearchModal,
        Tinput,
        vSelect
    },
    data() {
        return {
            car: {
                brand_id: 0,
                mark_id: 0,
                complectation_id: 0,
                color_id: 0,
                vin: '',
                year: '',
                packs: [],
                device_price: 0,
                devices: [],
                delivery_type_id: 0,
                marker_id: 0,
                delivery_stage_id: 0,
                production_at: 0,
                client: {},
                device_cost: 0,
                purchase: 0
            },
            packPrice: 0,
            complectPrice: 0,
            notFound: false,
            loading: true,
            urlId: this.$route.params.id,
            succes: false,
            succesMessage: null,
            previusPage: '/',
            search: {
                lastname: '',
                firstname: '',
                fathername: '',
                email: '',
                phone: '',
                page: 1,
            },
            clients: [],
            now_page: 1
        }
    },

    computed: {
        fullPrice() {
            return this.packPrice + this.complectPrice + parseInt(this.car.device_price)
        },

        hasClient() {
            if(this.car.hasOwnProperty('client') && this.car.client.hasOwnProperty('id'))
                return true
            return false
        },
    },

    mounted() {
        this.previusPage = this.prevRoute.fullPath
        if(this.urlId)
            this.loadData(this.urlId)
    },
    methods: {
        deleteClient() {
            this.car.client = ''
        },

        setClient(data) {
            Vue.set(this.car, 'client', data)
        },

        showModalClientFilter() {
            this.$refs['client-filter-modal'].$refs['client-filter-modal'].show()
            this.$refs['client-filter-modal'].search = this.search
        },

        //Объектпоиска в строку юрл
        searchToUrl() {
            var mas = this.search;
            var str = '';
            var objUrl = {}
            for(var key in mas)
                if(mas[key]) {
                    str+=key+'='+mas[key]+'&';
                    objUrl[key] = mas[key]
                }
            return str;
        },

        getDataModal(data) {
            this.search.page = 1
            this.now_page = 1
            this.searClients()
        },

        cnangePage(i) {
            this.search.page = i;
            this.now_page = i
            this.loadData()
        },

        searClients() {
            var url = '/api/clients'
            url += '?'+this.searchToUrl()
            list(this, url, 'clients', 'message')
        },

        formatPrice(param) {
            return number_format(param,0,'',' ', 'руб.');
        },

        onUpdateDevice(data) {
            this.car.devices = data.devices,
            this.car.device_price = data.price
            this.car.device_cost = data.cost
        },

        onUpdateColor(markColorId) {
            this.car.color_id = markColorId
        },

        onUpdatePack(data) {
            this.car.packs = data.data
            this.packPrice = data.packPrice
        },

        loadData(id) {
            edit(this, '/api/cars/' + this.urlId + '/edit', 'car', 'message')
        },

        updateData(id) {
            var obj = this.car
            obj._method = 'patch'
            update(this, '/api/cars/' + this.urlId, this.car, 'car', 'message')
        },

        storeData() {
            storage(this, '/api/cars/', this.car, 'car', 'message', 'urlId', 'cars')
        },

        getConfig() {
            return {
                'content-type': 'application/json'
            }
        },



        getComplectationPrice() {
            if(this.car.complectation_id > 0)
                axios.get('/api/services/price/complectation/'+this.car.complectation_id)
                .then( (res) => {
                    this.complectPrice = res.data.data
                })
                .catch((errors) => {
                    console.log(errors)
                })
        }


    },
    watch: {
        'car.complectation_id': {
            immediate: true,
            handler() {
                this.getComplectationPrice()
            },
        }
    },
    beforeRouteEnter(to, from, next) {
        next(vm => {
            vm.prevRoute = from;
        });
    },
}
</script>
