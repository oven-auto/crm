<template>
    <div id="pack-list">

        <message v-if="succes" :message="succesMessage"></message>

        <div class="row pb-3 d-flex align-items-center">
            <div class="col">
                <div class="h-title">Архивный склад</div>
            </div>
            <div class="col text-right">
                <button type="button" class="btn btn-success" @click="showModal" >
                    Поиск
                </button>
            </div>
            <div class="col-12" >
                <FilterBreadCrumbs :search="search" :type="'cars'" @updateParent="getDataModal"></FilterBreadCrumbs>
            </div>
        </div>

        <spin v-if="loading"></spin>

        <table v-else class="table car-list-table table-hover">

            <thead class="thead-dark">
                <tr>
                    <th style="width: 80px;">#{{data.length}}</th>
                    <th>VIN</th>
                    <th>Модель</th>
                    <th>Год</th>
                    <th class="text-center">Цвет</th>
                    <th colspan="1">Прайс-лист</th>
                    <th colspan="1">Цена продажи</th>
                    <th class="text-right">Дата архивации</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="item in data.cars" class="small-text ">
                    <td><router-link :to="toEdit + item.id">Open </router-link></td>
                    <td>
                        <div><big>{{item.vin}}</big></div>
                        <div>{{item.complectation.code}}</div>
                    </td>

                    <td>
                        <div>{{ item.brand.name }} {{ item.mark.name }} </div>
                        <div>{{ item.complectation.name }}{{ item.complectation.motor.size }} ({{ item.complectation.motor.power }}л.с.) </div>
                        <div>{{ item.complectation.motor.transmission.acronym }}{{ item.complectation.motor.driver.acronym }}</div>
                    </td>
                    <td>{{item.year}}</td>
                    <td class="text-center">
                        <div>
                            {{ item.color.code }}
                        </div>
                        <div>
                            <img :src="item.color.img">
                        </div>
                    </td>
                    <td>
                        <div>Кузов: {{ formatPrice(item.price.complectation_price) }}</div>
                        <div>Опции: {{ formatPrice(item.price.pack_price) }}</div>
                        <div>Допы: {{ formatPrice(item.price.device_price) }}</div>
                        <div><b>Итого: {{ formatPrice(item.price.full_price) }}</b></div>
                    </td>
                    <td>
                        <div>Кузов: {{ formatPrice(item.fixedprice.body_price) }}</div>
                        <div>Опции: {{ formatPrice(item.fixedprice.packs_price) }}</div>
                        <div>Допы: {{ formatPrice(item.fixedprice.equipments_price) }}</div>
                        <div><b>Итого: {{ formatPrice(item.fixedprice.total_price) }}</b></div>
                    </td>

                    <td class="text-right">
                        {{ getDate(item.deleted_at) }}
                    </td>
                </tr>
            </tbody>

        </table>

        <div class="">
            <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item"  v-bind:class="{active : (now_page == (index+1)) }" v-for="(item, index) in data.last_page">
                    <span class="page-link " @click="cnangePage(index+1)" >
                       {{ (index+1) }}
                    </span>
                </li>
            </ul>
            </nav>
        </div>

        <modal-window ref="modal" @updateParent="getDataModal"></modal-window>

    </div>
</template>

<script>

import Spin from '../spinner/SpinComponent';
import Message from '../alert/MessageComponent';
import ModalWindow from '../modal/CarFilterModal';
import FilterBreadCrumbs from '../html/breadcrumbs/FilterBreadCrumbs';

export default {
    name: 'car-list',
    components: {
        Spin,
        Message,
        ModalWindow,
        FilterBreadCrumbs
    },
    data() {
        return {
            data: [],
            loading: true,
            toEdit: '/cars/archive/',
            notFound: false,
            succes: false,
            succesMessage: null,
            firstPage: 1,
            lastPage: 1,
            currentPage: 1,
            pageArray: [],
            now_page: this.$route.params.page,
            search: {}
        }
    },
    mounted() {
        this.loadData()
    },

    methods: {
        getDate(str) {
            var date = new Date(str)
            var day = date.getDate()
            var month = date.getMonth()+1
            var year = date.getFullYear()
            return day +'.' + (month<10 ? '0'+month : month )  + '.' + year
        },

        getUrlParamStr() {
            var i = 0
            var url = []
            for(var key in this.$route.query)
                url.push(key+'='+this.$route.query[key])
            return url.join('&')
        },
        showModal: function () {
            this.$refs.modal.show = true
            // this.$refs.modal.brand = this.mark.brand_id
            // this.$refs.modal.loadData()
        },

        getDataModal(data) {
            this.search = data;
            this.$router.push('/cars/list/archive')
            this.$router.push('/cars/list/archive/'+1)
            var obj = {}
            for(var key in this.search){
                if(this.search[key] > 0 && this.search[key] != '')
                    obj[key] = this.search[key]
            }
            this.$router.push({query: obj})
        },

        formatPrice(price)
        {
            return number_format(price,0,'',' ','руб.')
        },

        loadData( ) {
            var url = '/api/cars'
            url += '?page='+this.now_page+'&archive=1'
            url += '&'+this.getUrlParamStr()
            list(this, url, 'data', 'message')
        }
    },
    watch: {
        '$route' (to, from) {
            this.now_page = to.params.page
            this.loadData()
        }
    }
}
</script>

<style scoped>
.car-list-table td{
    vertical-align: middle;
}
.car-list-table img{
    height: 50px;
}
.small-text{
    font-size: 0.7rem;
}
</style>
