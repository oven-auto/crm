<template>
    <div id="color-list">
        <div class="row pb-3 d-flex align-items-center">
            <div class="col">
                <div class="h-title">База клиентов</div>
            </div>
            <div class="col text-right">

                <router-link class="btn btn-primary" :to="'/clients/create'">Внести нового клиента</router-link>
                <button class="btn btn-success" @click="showModalClientFilter">Поиск</button>

            </div>

            <div class="col-12" >
                <FilterBreadCrumbs :search="search" :type="'clients'" @updateParent="getDataModal"></FilterBreadCrumbs>
            </div>
        </div>

        <spin v-if="loading"></spin>

        <table v-else class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th style="width: 80px;">#{{data.total}}</th>
                    <th>ФИО</th>
                    <th>Email</th>
                    <th>Телефон</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="item in data.clients" :key="'color'+item.id">
                    <td>
                        <router-link :to="toEdit + item.id">
                        Open
                        </router-link>
                    </td>
                    <td>{{item.lastname}} {{item.firstname}} {{item.fathername}}</td>
                    <td>{{item.email}}</td>
                    <td>{{phone_format(item.phone)}}</td>
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

        <ClientFilterModal ref="client-filter-modal" @updateParent="getDataModal"></ClientFilterModal>

    </div>
</template>

<script>

import Spin from '../spinner/SpinComponent';
import Message from '../alert/MessageComponent';
import ColorIcon from '../html/ColorIcon';
import BrandBadge from '../badge/BrandBadge';
import ClientFilterModal from '../modal/ClientFilterModal';
import FilterBreadCrumbs from '../html/breadcrumbs/FilterBreadCrumbs';

export default {
    name: 'color-list',
    components: {
        Spin,
        Message,
        ColorIcon,
        BrandBadge,
        ClientFilterModal,
        FilterBreadCrumbs
    },
    data() {
        return {
            data: [],
            loading: true,
            toEdit: '/clients/edit/',
            message: null,
            now_page: this.$route.params.page,
            search: {
                lastname: '',
                firstname: '',
                fathername: '',
                email: '',
                phone: '',
                page: 1,
            }
        }
    },
    mounted() {
        if(this.now_page == undefined)
            this.now_page = 1

        this.initSearchFromUrl()
        this.loadData()
    },
    methods: {
        phone_format(phone) {
            return phone_format(phone)
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
            this.$router.push('/clients/list?123')
            this.$router.replace({query: objUrl})
            return str;
        },

        cnangePage(i) {
            this.search.page = i;
            this.now_page = i
            this.loadData()
        },

        showModalClientFilter() {
            this.$refs['client-filter-modal'].$refs['client-filter-modal'].show()
            this.$refs['client-filter-modal'].search = this.search
        },

        loadData() {
            var url = '/api/clients'
            //url += '?page='+this.now_page
            url += '?'+this.searchToUrl()
            list(this, url, 'data', 'message')
        }
    }
}
</script>
