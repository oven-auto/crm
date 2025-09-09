<template>
    <div id="device-type-list">

        <message v-if="succes" :message="succesMessage"></message>

        <div class="row pb-3 d-flex align-items-center">
            <div class="col">
                <div class="h-title">Список категорий оборудования</div>
            </div>
            <div class="col text-right">
                <router-link class="btn btn-primary" :to="'/devicetypes/create'">Добавить новую категорию</router-link>
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
                <tr v-for="(type,i) in types" :key="'device_type_table'+type.id">
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
                            <DeviceTypeCount :device_type_id="type.id" :index="i" @deleteTrigger="deleteRow"></DeviceTypeCount>

                        </div>
                        <ion-icon class="drag-icon" name="ellipsis-vertical"></ion-icon>
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
import DeviceTypeCount from '../../indicators/DeviceTypeCount'

export default {
    name: 'device-type-list',
    components: {
        Spin,
        Message,
        draggable,
        DeviceTypeCount
    },
    data() {
        return {
            types: [],
            loading: true,
            toEdit: '/devicetypes/edit/',
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
            axios.patch('/api/services/sort/devicetypes', obj, this.getConfig())
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
            list(this, '/api/devicetypes', 'types', 'message')
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

<style scoped>

</style>
