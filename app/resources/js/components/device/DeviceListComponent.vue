<template>
    <div id="device-type-list">

        <div class="row pb-3 d-flex align-items-center">
            <div class="col">
                <div class="h-title">Список оборудования</div>
            </div>
            <div class="col text-right">

                <router-link class="btn btn-primary" :to="'/devices/create'">Добавить новое оборудование</router-link>
                <button class="btn btn-success" @click="showModalDeviceFilter">Поиск</button>

            </div>

            <div class="col-12" >
                <FilterBreadCrumbs :search="search" :type="'devices'" @updateParent="getDataModal"></FilterBreadCrumbs>
            </div>

        </div>

        <spin v-if="loading"></spin>

        <table v-else class="table table-hover device-table">
            <thead class="thead-dark">
                <tr>
                    <th style="width: 80px;">#{{devices.length}}</th>
                    <th>Название</th>
                    <th>ЦКУ</th>
                    <th>Фильтр</th>
                    <th>Тип</th>
                    <th>Бренд</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="(device,i) in devices" :key="'devicelist'+i">
                    <td>
                        <router-link :to="toEdit + device.id">
                            Open
                        </router-link>
                    </td>
                    <td>
                        <span v-if="device.image_count" style="border-bottom: dashed 1px #333" @click="showModalImage(device)">
                            {{ device.name }}
                        </span>
                        <span v-else>{{device.name}}</span>
                    </td>
                    <td>
                        <div v-if="device.install_target">
                            <span class="badge badge-grey" title="Целевой коеффициент установки">
                                ЦКУ: {{device.install_target}}
                            </span>
                            <br/>
                            <span class="badge badge-success" title="Фактический коеффициент установки">
                                ФКУ: {{device.install_target}}
                            </span>
                        </div>
                    </td>
                    <td style="">
                        <div v-if="device.filter.name">
                            {{ device.filter.name }}
                        </div>

                        <div v-else class="text-very-muted">
                            Фильтр не присвоен
                        </div>
                    </td>
                    <td style="width:150px;">
                        <span class="badge">
                            {{device.type.name }}
                        </span>
                        <span class="badge text-success d-block text-left">
                            {{device.tuning ? 'Доступно в тюнинге' : ''}}
                        </span>
                    </td>
                    <td style="width:100px;">
                        <div v-for="brand in device.brands">
                            <brand-badge :brand="brand"></brand-badge>
                        </div>
                    </td>
                    <td class="text-right">
                        <i class="fa fa-close text-danger" @click="deleteObj(device,i)"></i>
                    </td>
                </tr>
            </tbody>
        </table>

        <DeviceFilterModal ref="device-filter-modal" @updateParent="getDataModal"></DeviceFilterModal>

        <DeviceImageModal ref="device-image-modal"></DeviceImageModal>

    </div>
</template>

<script>
import Spin from '../spinner/SpinComponent';
import Message from '../alert/MessageComponent';
import BrandBadge from '../badge/BrandBadge';
import DeviceFilterModal from '../modal/DeviceFilterModal';
import FilterBreadCrumbs from '../html/breadcrumbs/FilterBreadCrumbs';
import DeviceImageModal from '../modal/DeviceImage'

export default {
    name: 'device-list',
    components: {
        Spin,
        Message,
        BrandBadge,
        DeviceFilterModal,
        FilterBreadCrumbs,
        DeviceImageModal
    },
    data() {
        return {
            devices: [],
            loading: true,
            toEdit: '/devices/edit/',
            message: null,
            search: {
                name: '',
                brand_id: 0,
                device_type_id: 0,
                device_filter_id: 0,
                tuning: 0,
                install_target: 0,
            },
        }
    },
    mounted() {
        this.initSearchFromUrl()
        this.loadData()
    },
    methods: {
        showModalImage(device) {
            this.$refs['device-image-modal'].$refs['device-image-modal'].show()
            this.$refs['device-image-modal'].device = device
        },
        deleteObj(obj,i) {
            var status = confirm('Вы действительно хотите удалить эту строку?')
            if(status) {
                this.loading = true
                axios.delete('/api/devices/'+obj.id)
                .then((res)=>{
                    this.devices.splice(i,1)
                    this.message = response.data.message;
                }).catch((errors) => {
                    this.message = errorsToStr(errors)
                }).finally(() => {
                    this.loading = false
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
        },
        //Действие в ответ на действие в модали
        getDataModal(data) {
            this.loadData()
        },
        //Открыть модаль и передать ей объект поиска в свойство
        showModalDeviceFilter() {
            this.$refs['device-filter-modal'].$refs['device-filter-modal'].show()
            this.$refs['device-filter-modal'].search = this.search
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
            this.$router.push('/devices/list?123')
            this.$router.replace({query: objUrl})
            return str;
        },

        loadData() {
            var url = '/api/devices?'+this.searchToUrl()
            list(this, url, 'devices', 'message')
        }
    }
}
</script>

<style scoped>
.device-table td{
    vertical-align:middle;
}
</style>
