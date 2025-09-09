<template>
    <div id="device-type-list">

        <message v-if="succes" :message="succesMessage"></message>

        <div class="row pb-3 d-flex align-items-center">
            <div class="col">
                <div class="h-title">Список фильтров по оборудованию</div>
            </div>
            <div class="col text-right">
                <router-link class="btn btn-primary" :to="'/devicefilters/create'">Создать новый фильтр</router-link>
            </div>
        </div>

        <spin v-if="loading"></spin>

        <table v-else class="table table-hover">
            <thead class="thead-dark">
                <tr>
                    <th style="width: 80px;">#{{types.length}}</th>
                    <th>Название</th>
                    <th></th>
                </tr>
            </thead>

            <draggable v-model="types" tag="tbody" :component-data="getComponentData()">
                <tr v-for="(type,i) in types">
                    <td>
                        <router-link :to="toEdit + type.id">
                            Open
                        </router-link>
                    </td>
                    <td>
                        {{ type.name }}


                    </td>

                    <td class="">
                        <div style="width: 80px; float:right;">
                            <DeviceFilterCount :device_filter_id="type.id" :index="i" @deleteTrigger="deleteRow"></DeviceFilterCount>
                            <ion-icon class="drag-icon" name="ellipsis-vertical"></ion-icon>
                        </div>
                    </td>
                </tr>
            </draggable>
        </table>
    </div>
</template>

<script>
import Spin from '../../spinner/SpinComponent';
import Message from '../../alert/MessageComponent';
import draggable from 'vuedraggable';
import DeviceFilterCount from '../../indicators/DeviceFilterCount';

export default {
    name: 'device-filter-list',
    components: {
        Spin,
        Message,
        draggable,
        DeviceFilterCount
    },
    data() {
        return {
            types: [],
            loading: true,
            toEdit: '/devicefilters/edit/',
            notFound: false,
            succes: false,
            succesMessage: null,
        }
    },
    mounted() {
        this.loadTypes()
    },
    methods: {
        inputChanged(value) {
            var oldIndex = value.oldIndex
            var newIndex = value.newIndex

            var data = {
                active: {
                    id: this.types[newIndex].id,
                },
                second: {
                    id: this.types[oldIndex].id,
                }
            }
            this.changeSort(data)
        },

        changeSort(obj) {
            this.loading = true
            axios.patch('/api/services/sort/devicefilters', obj, this.getConfig())
            .then((res)=>{
                this.loadTypes()
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
        loadTypes() {
            list(this, '/api/devicefilters', 'types', 'message')
        },

        getConfig() {
            return {
                'content-type': 'application-json'
            }
        },

        deleteRow(data) {
            this.types.splice(data.index,1)
            makeToast(this,data.message)
        }
    }
}
</script>
