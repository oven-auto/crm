<template>
    <div id="property-list">
        <div class="row pb-3 d-flex align-items-center">
            <div class="col">
                <div class="h-title">Список характеристик</div>
            </div>
            <div class="col text-right">
                <router-link class="btn btn-primary" :to="'/properties/create'">Добавить новую характеристику</router-link>
            </div>
        </div>

        <spin v-if="loading"></spin>

        <table v-else class="table table-hover">
            <thead class="thead-dark">
            <tr>
                <th style="width: 80px;">#</th>
                <th>Название</th>
            </tr>
            </thead>

            <draggable v-model="properties" tag="tbody" :component-data="getComponentData()" >
            <tr v-for="property in properties" :key="'property'+property.id">
                <td>
                    <router-link :to="toEdit + property.id">
                        Open
                    </router-link>
                </td>
                <td>
                    {{ property.name }}
                    <ion-icon class="drag-icon pr-3" name="ellipsis-vertical"></ion-icon>
                </td>
            </tr>
            </draggable>
        </table>
    </div>
</template>

<script>
import Spin from '../spinner/SpinComponent';
import draggable from 'vuedraggable';

export default {
    name: 'property-list',
    components: {
        Spin,
        draggable
    },
    data() {
        return {
            toEdit: '/properties/edit/',
            loading: true,
            properties: [],
            notFound: false,
            message: '',
        }
    },
    mounted() {
        this.loadProperty()
    },
    methods: {

        inputChanged(value) {
            var oldIndex = value.oldIndex
            var newIndex = value.newIndex

            var data = {
                active: {
                    id: this.properties[newIndex].id,
                },
                second: {
                    id: this.properties[oldIndex].id,
                }
            }
            this.changeSort(data)
        },

        changeSort(obj) {
            this.loading = true
            axios.patch('/api/services/sort/properties', obj, getConfig())
            .then((res)=>{
                this.loadProperty()
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

        loadProperty() {
            list(this, '/api/properties', 'properties', 'message')
        }
    }
}
</script>
