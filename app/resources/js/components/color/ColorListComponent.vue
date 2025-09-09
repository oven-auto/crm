<template>
    <div id="color-list">
        <div class="row pb-3 d-flex align-items-center">
            <div class="col">
                <div class="h-title">Палитра цветов</div>
            </div>
            <div class="col text-right">

                <router-link class="btn btn-primary" :to="'/colors/create'">Добавить новый цвет</router-link>
                <button class="btn btn-success" @click="showModalColorFilter">Поиск</button>

            </div>

            <div class="col-12" >
                <FilterBreadCrumbs :search="search" :type="'colors'" @updateParent="getDataModal"></FilterBreadCrumbs>
            </div>
        </div>

        <spin v-if="loading"></spin>

        <table v-else class="table table-hover">
            <thead class="table-dark">
            <tr>
                <th style="width: 80px;">#{{data.length}}</th>
                <th>Название</th>
                <th>Бренд</th>
                <th>Код</th>
                <th>Иконка</th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="item in data" :key="'color'+item.id">
                <td>
                    <router-link :to="toEdit + item.id">
                        Open
                    </router-link>
                </td>
                <td>{{ item.name }}</td>
                <td><brand-badge :brand="item.brand"></brand-badge></td>
                <td>{{ item.code }}</td>
                <td>
                    <ColorIcon :colors="item.web"></ColorIcon>
                </td>
            </tr>
            </tbody>

        </table>

        <ColorFilterModal ref="color-filter-modal" @updateParent="getDataModal"></ColorFilterModal>
    </div>
</template>

<script>

import Spin from '../spinner/SpinComponent';
import Message from '../alert/MessageComponent';
import ColorIcon from '../html/ColorIcon';
import BrandBadge from '../badge/BrandBadge';
import ColorFilterModal from '../modal/ColorFilterModal';
import FilterBreadCrumbs from '../html/breadcrumbs/FilterBreadCrumbs';

export default {
    name: 'color-list',
    components: {
        Spin,
        Message,
        ColorIcon,
        BrandBadge,
        ColorFilterModal,
        FilterBreadCrumbs
    },
    data() {
        return {
            data: [],
            loading: true,
            toEdit: '/colors/edit/',
            message: null,
            search: {
                code: '',
                name: '',
                brand_id: 0
            }
        }
    },
    mounted() {
        this.initSearchFromUrl()
        this.loadData()
    },
    methods: {
        //инициализировать объект поиска из параметров гет юрл строки
        initSearchFromUrl() {
            var i = 0
            var url = []
            for(var key in this.$route.query)
                this.search[key] = this.$route.query[key]
        },
        //Действие в ответ на действие в модали
        getDataModal(data) {
            this.loadData()
        },
        //Открыть модаль и передать ей объект поиска в свойство
        showModalColorFilter() {
            this.$refs['color-filter-modal'].$refs['color-filter-modal'].show()
            this.$refs['color-filter-modal'].search = this.search
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
            this.$router.push('/colors/list?123')
            this.$router.replace({query: objUrl})
            return str;
        },

        loadData() {
            var url = '/api/colors?'+this.searchToUrl()
            list(this, url, 'data','message')
        }
    }
}
</script>
