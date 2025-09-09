<template>
<div id="mark-list">
    <div class="row pb-3">
        <div class="col">
            <div class="h5">Список моделей</div>
        </div>
        <div class="col text-right">
            <router-link class="btn btn-primary" :to="'/marks/create'">Создать новую модель</router-link>
        </div>
    </div>

    <spin v-if="loading"></spin>

    <!-- <table v-else class="table">
        <tr>
            <th style="width: 80px;">#</th>
            <th>Название</th>
            <th>Статус</th>
        </tr>

        <tr v-for="mark in marks">
            <td>
                <router-link :to="toEdit + mark.id">
                    Open
                </router-link>
            </td>
            <td>
                <brand-badge :brand="mark.brand"></brand-badge>
                {{ mark.prefix ? mark.prefix : '' }} {{ mark.name  }}
            </td>
            <td>
                <span class="badge badge-success" v-if="mark.status">
                    Активна
                </span>
                <span v-else class="badge badge-danger">
                    Отключена
                </span>
            </td>
        </tr>
    </table> -->

    <draggable v-model="marks" tag="div" :component-data="getComponentData()" class="row marks-row">
        <div class="col-3 mb-3"  v-for="(mark,i) in marks" :key="'mark-list'+i">
            <router-link :to="toEdit + mark.id">
            <div class="border rounded py-3 mark-col  d-flex justify-content-center" style="height: 100%;">

                <div :class="{'gray-image': mark.status==0}">

                        <div class="pl-3">
                            {{mark.brand.name}}
                            <ion-icon class="drag-icon pr-3" name="ellipsis-vertical"></ion-icon>
                        </div>



                    <div class="text-left pl-3" style="white-space:nowrap; overflow:hidden;">


                        <b>{{mark.prefix}} {{mark.name}}</b>

                    </div>
                    <img :src="(mark.icon.image)" >
                    <div class="text-muted text-center">
                        <small>{{mark.bodywork.name}}</small>
                    </div>
                </div>
            </div>
            </router-link>
        </div>
    </draggable>

</div>

</template>

<script>
import Spin from '../spinner/SpinComponent';
import BrandBadge from '../badge/BrandBadge';
import draggable from 'vuedraggable'

export default {
    name: 'mark-list',
    components: {
        Spin,
        BrandBadge,
        draggable
    },
    data() {
        return {
            toEdit: '/marks/edit/',
            loading: true,
            marks: [],
            notFound: false,
            message: ''
        }
    },
    mounted() {
        this.loadData()
    },
    methods: {
        inputChanged(value) {
            var oldIndex = value.oldIndex
            var newIndex = value.newIndex

            var data = {
                active: {
                    id: this.marks[newIndex].id,
                },
                second: {
                    id: this.marks[oldIndex].id,
                }
            }
            this.changeSort(data)
        },

        changeSort(obj) {
            this.loading = true
            axios.patch('/api/services/sort/marks', obj, this.getConfig())
            .then((res)=>{
                this.loadData()
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

        writeImage(url) {
            return storageUrl+url;
        },

        loadData() {
            list(this, '/api/marks','marks','message')
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
.marks-row img{
    width: 100%;
}
.marks-row a{
    text-decoration: none;
    color: inherit;
}
.mark-col{
    transition: transform 0.3s, box-shadow 0.3s;
    cursor: pointer;
}
.mark-col:hover{
    transform: scale(1.02,1.02);
    box-shadow: 0 0 30px rgb(226, 226, 226);
}
.gray-image{
    opacity: .3;
}
</style>
