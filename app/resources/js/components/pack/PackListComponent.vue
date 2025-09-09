<template>
    <div id="pack-list">

        <message v-if="succes" :message="succesMessage"></message>

        <div class="row pb-3 d-flex align-items-center" style="border 1px solid">
            <div class="col">
                <div class="h-title">Cписок опций</div>
            </div>
            <div class="col text-right">
                <router-link class="btn btn-primary" :to="'/packs/create'">Добавить новую опцию</router-link>
                <button class="btn btn-success" @click="showModalPackFilter">Поиск</button>
            </div>

            <div class="col-12" >
                <FilterBreadCrumbs :search="search" :type="'packs'" @updateParent="getDataModal"></FilterBreadCrumbs>
            </div>

        </div>

        <spin v-if="loading"></spin>

        <table v-else class="table pack-table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#{{data.length}}</th>
                    <th>Цена</th>
                    <th>Код</th>
                    <th>Оборудование</th>
                    <th>Модель</th>
                    <th></th>
                </tr>
            </thead>

            <tbody class="">
            <tr v-for="(item,i) in data" :key="'plp'+i">
                <td>
                    <router-link :to="toEdit + item.id">
                        Open
                    </router-link>
                </td>

                <td class="py-0 pr-0">
                    <PriceChange v-model="item.price" :id="item.id" :url="'/api/services/price/pack'"></PriceChange>
                </td>

                <td>
                    {{ item.code }}
                    <div class="text-muted">{{ item.name }}</div>
                </td>

                <td style="width: 50%;overflow:hidden;">

                    <span v-for="(device,k) in item.devices" class="badge badge-secondary mr-1" :key="'dliplp'+k">
                        {{ device.name }}
                    </span>
                </td>

                <td>
                    <div v-if="item.marks.length">
                        <div v-for="(mark,i) in item.marks" :key="'pack-marks'+i">
                            <brand-badge-mark :brand="item.brand" :mark="mark.name"></brand-badge-mark>
                        </div>
                    </div>
                    <div v-else>
                         <brand-badge :brand="item.brand" ></brand-badge>
                    </div>
                </td>

                <td>
                    <i class="fa fa-close text-danger" @click="deletePack(item,i)"></i>
                </td>

            </tr>
            </tbody>

        </table>

        <PackModalFilter ref="pack-filter-modal" @updateParent="getDataModal"></PackModalFilter>
    </div>
</template>

<script>

import Spin from '../spinner/SpinComponent';
import Message from '../alert/MessageComponent';
import BrandBadgeMark from '../badge/BrandMarkBadge';
import BrandBadge from '../badge/BrandBadge';
import PackModalFilter from '../modal/PackFilterModal';
import FilterBreadCrumbs from '../html/breadcrumbs/FilterBreadCrumbs';
import PriceChange from '../html/PriceChange/PriceChange';

export default {
    name: 'color-list',
    components: {
        Spin,
        Message,
        BrandBadge,
        PackModalFilter,
        FilterBreadCrumbs,
        PriceChange,
        BrandBadgeMark
    },

    data() {
        return {
            data: [],
            loading: true,
            toEdit: '/packs/edit/',
            notFound: false,
            succes: false,
            message: null,
            search: {
                code: '',
                brand_id: 0,
                mark_id: 0,
            },
        }
    },
    mounted() {
        this.initSearchFromUrl()
        this.loadData()
    },
    methods: {
        deletePack(obj, index) {
            var status = confirm('Вы действительно хотите удалить эту строку?')
            if(status) {
                axios.delete('/api/packs/'+obj.id)
                .then((res)=>{
                    this.data.splice(index,1)
                    makeToast(this,res.data.message)
                }).catch((errors) => {

                }).finally(() => {

                })
            }
        },
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
        showModalPackFilter() {
            this.$refs['pack-filter-modal'].$refs['pack-filter-modal'].show()
            this.$refs['pack-filter-modal'].search = this.search
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
            this.$router.push('/packs/list?123')
            this.$router.replace({query: objUrl})
            return str;
        },

        loadData() {
            list(this,'/api/packs?' + this.searchToUrl(), 'data', 'message')
        },

        getConfig() {
            return {
                'content-type': 'application-json'
            }
        },
    }
}
</script>

<style scoped>
.pack-table .badge{
    white-space:normal;
}
.pack-table td{
    vertical-align: middle;
}

</style>
