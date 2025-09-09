<template>
    <div id="motor-type-list">

        <message v-if="succes" :message="succesMessage"></message>

        <div class="row pb-3 d-flex align-items-center">
            <div class="col">
                <div class="h-title">Список агрегатов</div>
            </div>
            <div class="col text-right">
                <router-link class="btn btn-primary" :to="'/motors/create'">Добавить новый мотор</router-link>
                <button class="btn btn-success" @click="showModalFilter">Поиск</button>
            </div>

            <div class="col-12" >
                <FilterBreadCrumbs :search="search" :type="'motors'" @updateParent="getDataModal"></FilterBreadCrumbs>
            </div>
        </div>

        <spin v-if="loading"></spin>

        <table v-else class="table table-hover">
            <thead class="thead-dark">
            <tr>
                <th style="width: 80px;">#{{motors.length}}</th>
                <th>Бренд</th>
                <th>Спецификация агрегата</th>
                <th>Модель ДВС</th>
                <th>Тип двигателя</th>
                <th>Класс токсичности</th>
                <th>Модель КПП</th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="(item,i) in motors" :key="'motor'+i">
                <td>
                    <router-link :to="toEdit + item.id">
                        Open
                    </router-link>
                </td>
                <td><brand-badge :brand="item.brand"></brand-badge></td>

                <td>{{ item.size }}{{ item.type.acronym }} ({{ item.power }} л.с.) {{item.valve}} кл. {{ item.transmission.acronym }} {{ item.driver.acronym }}</td>
                <td>{{ item.name }}</td>
                <td>{{ item.type.name }}</td>
                <td>{{item.toxic.name}}</td>
                <td>{{item.transmission_name}}</td>
            </tr>
            </tbody>
        </table>

        <MotorFilterModal ref="motor-filter-modal" @updateParent="getDataModal"></MotorFilterModal>

    </div>
</template>

<script>

import Spin from '../spinner/SpinComponent';
import Message from '../alert/MessageComponent';
import BrandBadge from '../badge/BrandBadge';
import FilterBreadCrumbs from '../html/breadcrumbs/FilterBreadCrumbs';
import MotorFilterModal from '../modal/MotorFilterModal'

export default {
    name: 'motor-list',
    components: {
        Spin,
        Message,
        BrandBadge,
        MotorFilterModal,
        FilterBreadCrumbs
    },
    data() {
        return {
            motors: [],
            loading: true,
            toEdit: '/motors/edit/',
            notFound: false,
            succes: false,
            succesMessage: null,
            search: {
                name: '',
                brand_id: 0,
                motor_transmission_id: 0,
                motor_driver_id: 0,
                motor_type_id: 0,
                motor_toxic_id: 0
            }
        }
    },
    mounted() {
        //this.search = {name: ''}
        this.initSearchFromUrl()
        this.loadData()
    },
    methods: {

        showModalFilter() {
            this.$refs['motor-filter-modal'].$refs['motor-filter-modal'].show()
            this.$refs['motor-filter-modal'].search = this.search
        },

        initSearchFromUrl() {
            var i = 0
            var url = []
            for(var key in this.$route.query)
                this.search[key] = this.$route.query[key]
        },

        getDataModal(data) {
            //this.search = data
            this.loadData()
        },

        //Объект поиска в строку юрл
        searchToUrl() {
            var mas = this.search;
            var str = '';
            var objUrl = {}
            for(var key in mas)
                if(mas[key]) {
                    str+=key+'='+mas[key]+'&';
                    objUrl[key] = mas[key]
                }
            this.$router.push('/motors/list?123')
            this.$router.replace({query: objUrl})
            return str;
        },

        loadData() {
            var url = '/api/motors?' + this.searchToUrl()
            list(this, url, 'motors','message')
        },
    }
}
</script>
