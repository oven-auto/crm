<template>
    <div id="pack-list">

        <message v-if="succes" :message="succesMessage"></message>

        <div class="row pb-3 d-flex align-items-center">
            <div class="col">
                <div class="h-title">Актуальный склад</div>
            </div>
            <div class="col text-right">

                <router-link class="btn btn-primary" :to="'/cars/create'">Добавить новый автомобиль</router-link>
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
                    <th style="width: 80px;">#{{data.total}}</th>
                    <th>VIN</th>
                    <th>Модель</th>
                    <th>Год</th>
                    <th class="text-center">Цвет</th>
                    <th colspan="2">Детализация цены</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="(item,i) in data.cars" :key="'car'+i" class="small-text ">
                    <td>
                        <router-link :to="toEdit + item.id">
                            <img :src="item.color.img">
                        </router-link>
                    </td>


                    <td>
                        <div v-if="loading==false">
                            <div v-if="item.delivery!=null">
                                <div :class="getCssCount(item.delivery.delivery_type_id)">
                                    {{ item.delivery.type.name }}
                                </div>
                            </div>
                            <div>
                                <router-link :to="'/marks/edit/'+item.mark.id">
                                    {{ item.brand.name }} {{ item.mark.name }}
                                </router-link>
                            </div>
                            <div>{{item.vin}}</div>
                            <div>{{ item.year }}</div>
                        </div>
                    </td>

                    <td>
                        <div>
                            <router-link :to="'/complectations/edit/'+item.complectation.id">{{ item.complectation.name }}</router-link>
                        </div>
                        <div>
                            {{ item.complectation.motor.size }}{{item.complectation.motor.type.acronym}}
                            ({{ item.complectation.motor.power }}л.с.)
                            {{ item.complectation.motor.valve }}кл.
                            {{ item.complectation.motor.transmission.acronym }}
                            {{ item.complectation.motor.driver.acronym }}
                        </div>
                        <div>{{item.complectation.code}}</div>
                        <div class="text-muted">
                            <div v-if="item.packs.length">
                                <span v-for="(itemPack,i) in item.packs" :key="'car-pack'+item.id+'pack'+i">
                                    {{itemPack.code}}
                                </span>
                            </div>
                            <div v-else>
                                Опции отсутствуют
                            </div>
                        </div>
                    </td>

                    <td>
                        <div>Кузов: {{ formatPrice(item.price.complectation_price) }}</div>
                        <div>Опции: {{ formatPrice(item.price.pack_price) }}</div>
                        <div>Воздух: 0 руб.</div>
                        <div>Допы: {{ formatPrice(item.price.device_price) }}</div>
                    </td>

                    <td>
                        <div>
                            Прайс:
                            <span v-if="!item.complectation.price_status">{{ formatPrice(item.price.full_price) }}</span>
                            <span v-else class="font-blood">Переоценка</span>
                        </div>

                        <div>
                            Выручка: ??? руб.
                        </div>
                        <div>
                            Закупка:
                            {{ formatPrice(item.purchase) }}
                        </div>
                        <div>
                            Маржа:
                            {{formatPrice(item.price.margin_price)}}
                        </div>
                    </td>

                    <td>
                        <div>{{ (getStage(item)) }}</div>
                        <div>
                            <div v-if="item.marker">{{item.marker}}</div>
                            <div v-else>Без контрмарки</div>
                        </div>
                        <div>{{item.client ? 'Клиентский' : 'Свободный'}}</div>
                        <div>Оплачен</div>
                    </td>

                    <td>
                        <div v-if="item.client.id">
                            <div>
                                <router-link :to="'/clients/edit/'+item.client.id">
                                    {{item.client.lastname+' '+item.client.firstname}}
                                </router-link>
                            </div>
                            <div>
                                {{phone_format(item.client.phone)}}
                            </div>
                            <div>
                                {{item.client.email}}
                            </div>
                            <div>
                                Менеджер
                            </div>
                        </div>

                        <div v-else>
                            <div>Покупатель</div>
                            <div>Телефон</div>
                            <div>Email</div>
                            <div>Менеджер</div>
                        </div>
                    </td>

                    <td class="text-right ">
                        <span  class="badge badge-danger" @click="deleteCar(item.id, i)">В архив</span>
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

        <modal-window ref="car_filter_modal" @updateParent="getDataModal"></modal-window>

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
    computed: {

    },
    data() {
        return {
            data: [],
            loading: true,
            toEdit: '/cars/edit/',
            notFound: false,
            succes: false,
            succesMessage: null,
            firstPage: 1,
            lastPage: 1,
            currentPage: 1,
            pageArray: [],
            now_page: this.$route.params.page,
            search: {
                brand_id : 0,
                mark_id : 0,
                complectation_id: 0,
                vin : '',
                delivery_type_id : 0,
                delivery_stage_id : 0,
                revaluation : 0,
                page: 1
            },
        }
    },
    mounted() {

        if(this.now_page == undefined){
            this.now_page = 1
        }

        this.initSearchFromUrl()
        this.loadData()
    },

    methods: {
        phone_format(phone) {
            return phone_format(phone)
        },

        getDate(str) {
            var date = new Date(str)
            return date.getDate() +'.' + (date.getMonth()<10 ? '0'+date.getMonth() : date.getMonth() )  + '.' + date.getFullYear()
        },
        getStage(obj) {
            if(isObject(obj.production.production_at))
                return obj.delivery.stage.name;
            var date = new Date(obj.production.production_at)
            var dateStr = date.getDate() +'.' + (date.getMonth()<10 ? '0'+date.getMonth() : date.getMonth() )  + '.' + date.getFullYear()
            if(obj.production.production_at!='')
                return 'Сборка: '+dateStr
        },

        deleteCar(id, index) {
            var status = confirm('Уверены, что хотите поместить этот автомобиль в архив?')
            if(status) {
                axios.delete('/api/cars/'+id)
                .then(res => {
                    this.message = res.data.message
                    this.data.cars.splice(index, 1)
                }).catch(errors => {
                    makeToast(this,'Ошибка!')
                }).finally(() => {
                    makeToast(this,this.message)
                })
            }
        },

        //инициализировать объект поиска из параметров гет юрл строки
        initSearchFromUrl() {
            var i = 0
            var url = []
            for(var key in this.$route.query)
                this.search[key] = this.$route.query[key]
            if(this.search.page)
                this.now_page = this.search.page
        },
        //Действие в ответ на действие в модали
        getDataModal(data) {
            this.search.page = 1
            this.now_page = 1
            this.loadData()
        },
        //Открыть модаль и передать ей объект поиска в свойство
        showModal() {
            this.$refs.car_filter_modal.$refs.car_filter_modal.show();
            this.$refs.car_filter_modal.search = this.search;
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
            this.$router.push('/cars/list?123')
            this.$router.replace({query: objUrl})
            return str;
        },

        cnangePage(i) {
            this.search.page = i;
            this.now_page = i
            this.loadData()
        },

        formatPrice(price)
        {
            return number_format(price,0,'',' ','руб.')
        },

        loadData( ) {
            var url = '/api/cars'
            url += '?page='+this.now_page
            url += '&'+this.searchToUrl()
            list(this, url, 'data', 'message')
        },

        getCssCount(id) {
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
    },
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



