<template>
    <div id="property-list">
        <div class="row pb-3 d-flex align-items-center">
            <div class="col">
                <div class="h-title">Список комплектаций</div>
            </div>
            <div class="col text-right">

                <router-link class="btn btn-primary" :to="'/complectations/create'">Добавить новую комплектацию</router-link>
                <button class="btn btn-success" @click="showModalComplectationFilter">Поиск</button>

            </div>

            <div class="col-12" >
                <FilterBreadCrumbs :search="search" :type="'complectations'" @updateParent="getDataModal"></FilterBreadCrumbs>
            </div>
        </div>

        <spin v-if="loading"></spin>

        <table v-else class="table table-hover complectation-table">
            <thead class="thead-dark">
            <tr>
                <th style="width: 80px;">#{{complectations.length}}</th>

                <th>Модель</th>

                <th>Название</th>
                <th colspan="">Прайс-лист <br/>и публикация</th>
                <th>Модератор</th>
                <th></th>
                <th style="width: 100px; text-align:right" >
                    <label class="checkbox m-0" :title="'Статус'">
                        <input class="device-checkbox-toggle" type="checkbox" v-bind:value="status" v-model="status" @change="getByStatus()">
                        <div class="checkbox__text" style="">
                            <div>
                                Статус
                            </div>
                        </div>
                    </label>
                </th>
            </tr>
            </thead>

            <draggable v-model="complectations" tag="tbody" :component-data="getComponentData()">
            <tr v-for="item in complectations" :key="item.id" >
                <td>
                    <router-link :to="toEdit + item.id">
                        Open
                    </router-link>
                </td>

                <td><brand-badge-mark :brand="item.brand" :mark="item.mark.name" :block="1"></brand-badge-mark></td>


                <td>
                    {{ item.code }}<br/>
                    {{ item.name }}
                    {{item.motor.size+item.motor.type.acronym}} ({{item.motor.power}}л.с.)  {{item.motor.valve}}кл.
                    {{item.motor.transmission.acronym}}
                    {{item.motor.driver.acronym}}
                </td>

                <td class="py-0" >
                    <div class="" style="width:200px;">
                        <div class="">
                            <label class="checkbox m-0 checkbox-red">
                                <input class="device-checkbox-toggle" type="checkbox"
                                    v-bind:value="Math.abs(item.price_status-1)" v-model="item.price_status"
                                    @change="changeComplectPrice(item.id,item.price_status,item)"
                                >
                                <div class="checkbox__text" style="">
                                    <div>
                                        &nbsp
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div v-if="item.price_status==1" class="text-danger">
                            На переоценке
                        </div>

                        <PriceChange @priceSend="loadModerator(item.id,item)" v-else :id="item.id" v-model="item.price" :current="item" :url="'/api/services/price/complectation'"></PriceChange>

                    </div>
                </td>

                <td>
                    <div v-if="item.lastmoderator.user && item.lastmoderator.user.name">
                        {{item.lastmoderator.user.name}}<br/>
                        {{getCreateDate(item.lastmoderator.created_at)}}
                    </div>
                </td>

                <td class="py-0">
                    <CarsComplectCount :complectation_id="item.id"></CarsComplectCount>
                </td>

                <td>
                    <div style="width: 100px;">
                        <complectation-status v-model="item.status" :id="item.id"></complectation-status>
                        <ion-icon class="drag-icon" name="ellipsis-vertical"></ion-icon>
                    </div>
                </td>
            </tr>
            </draggable>
        </table>

        <ComplectationFilter ref="modal" @updateParent="getDataModal"></ComplectationFilter>
    </div>
</template>

<script>
import draggable from 'vuedraggable';

import Spin from '../spinner/SpinComponent';
import ComplectationStatus from './ComplectationStatus';
import ComplectationFilter from '../modal/ComplectationFilterModal';
import BrandBadge from '../badge/BrandBadge';
import FilterBreadCrumbs from '../html/breadcrumbs/FilterBreadCrumbs';
import CarsComplectCount from '../indicators/CarsComplectCount';
import PriceChange from '../html/PriceChange/PriceChange';
import BrandBadgeMark from '../badge/BrandMarkBadge';

export default {
    name: 'complectation-list',
    components: {
        Spin,
        ComplectationStatus,
        ComplectationFilter,
        BrandBadge,
        draggable,
        FilterBreadCrumbs,
        CarsComplectCount,
        PriceChange,
        BrandBadgeMark
    },
    data() {
        return {
            statusPriceClick: 0,
            toEdit: '/complectations/edit/',
            loading: true,
            complectations: [],
            notFound: false,
            search: {
                brand_id: 0,
                mark_id: 0,
                name: '',
                code: '',
                status: 0
            },
            now_page: this.$route.params.page,
            status: 1,
            currentModelCssLine: 0,
            currentChange: {},
            message: ''
        }
    },
    mounted() {
        this.initSearchFromUrl()
        this.loadData()
    },

    computed: {

    },

    methods: {
        loadModerator(id,obj) {
            axios.get('/api/moderator/complectation/'+id)
            .then(res => {
                obj.lastmoderator = res.data.data
            }).catch(errors => {

            }).finally(() => {

            })
        },

        getCreateDate(str) {
            return transformLaravelDate(str)
        },

        getByStatus() {
            this.search.status = Number(this.status)
            this.loadData()
        },

        changeComplectPrice(id,price_status,itemObj) {
            var obj = {
                id: id,
                price_status: price_status
            };
            axios.patch('/api/services/price/complectation/pricestatus',obj)
            .then(res => {
                this.loadModerator(id,itemObj)
            }).catch(errors => {

            }).finally(()=>{

            })
        },

        //инициализировать объект поиска из параметров гет юрл строки
        initSearchFromUrl() {
            var i = 0
            var url = []
            for(var key in this.$route.query)
                this.search[key] = this.$route.query[key]
            this.status = this.search.status
        },
        //Действие в ответ на действие в модали
        getDataModal(data) {
            this.loadData()
        },
        //Открыть модаль и передать ей объект поиска в свойство
        showModalComplectationFilter() {
            this.$refs.modal.show = true;
            this.$refs.modal.search = this.search;
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
            this.$router.push('/complectations/list?123')
            this.$router.replace({query: objUrl})
            return str;
        },

        formatPrice(price) {
            return number_format(price,0,'',' ','руб.')
        },

        loadData() {
            var url = '/api/complectations?'+this.searchToUrl()
            list(this, url, 'complectations','message')
        },

        inputChanged(value) {
            var oldIndex = value.oldIndex
            var newIndex = value.newIndex

            var data = {
                active: {
                    id: this.complectations[newIndex].id,
                },
                second: {
                    id: this.complectations[oldIndex].id,
                }
            }

            this.changeSort(data)
        },

        changeSort(obj) {
            this.loading = true
            axios.patch('/api/services/sort/complectations', obj, this.getConfig())
            .then((res)=>{
                // var data = res.data.data
                // for(var i in data) {
                //     this.complectations.find(item => item.id == i).sort = data[i];
                //     console.log(this.complectations.find(item => item.id == i))
                //     console.log(data[i])
                // }
                this.loadData()
            })
            .catch((error)=>{

            })
            .finally(()=>{
                this.loading = false
            })
        },

        getComponentData() {
            return {
                on: {
                    update: this.inputChanged
                },
                attrs:{
                    wrap: true
                },
                props: {
                    value: this.activeNames
                }
            };
        },

        getConfig() {
            return {
                'content-type': 'application-json'
            }
        },
    },
    watch: {

        // '$route' (to, from) {
        //     this.now_page = to.params.page
        //     this.loadData()
        // },
        // status(to,from) {
        //     //this.now_page = to.params.page
        //     this.loadData()
        // }
    }
}
</script>

<style scoped>
.complectation-table tr{
    cursor: pointer;
}
table td{
    vertical-align: middle
}
.current-model-line td{
    border-top: 1px solid #f00;
}
</style>
