<template>
<div id="form-section-list">
    <message v-if="succes" :message="succesMessage"></message>

    <div class="row pb-3">
        <div class="col">
            <div class="h5">Формы обратной связи</div>
        </div>
    </div>

    <spin v-if="loading"></spin>

    <div v-else class="">
        <div class="row">
            <div class="col">
                <router-link :to="'/forms/create'">Создать корневой раздел</router-link>
            </div>
        </div>
        <div class="row d-block">
            <div class="col" v-for="item in sortSections" :key="'formsection'+item.id">
                <div class="d-flex align-items-center hover" :style="'padding-left:'+item.otstup+'px'">
                    {{item.name}}
                    <router-link class="d-flex align-items-center" :to="'/forms/edit/'+item.id">
                        <ion-icon class="icon red-count" name="create-outline" v-tooltip:top="'Редактировать'"></ion-icon>
                    </router-link>
                    <router-link class="d-flex align-items-center" :to="'/forms/create?parent_id='+item.id+'&brand_id='+item.brand_id">
                        <ion-icon class="icon green-count" name="documents-outline" v-tooltip:top="'Создать подраздел'"></ion-icon>
                    </router-link>
                    <router-link class="d-flex align-items-center" :to="'/forms/formcreate?section_id='+item.id" v-if="!item.childrens">
                        <ion-icon class="icon blue-count" name="mail-open-outline" v-tooltip:top="'Создать форму'"></ion-icon>
                    </router-link>

                    <span class="d-flex align-items-center">
                        <ion-icon name="trash-bin-outline" class="icon" style="background: red;color:#fff" @click="deleteSection(item.id)"></ion-icon>
                    </span>
                </div>

                <div v-if="item.form">
                    <draggable v-model="item.form" tag="div" :component-data="getComponentData()" >
                    <div v-for="(itemForm,k) in item.form" :style="'padding-left:'+(item.otstup+50)+'px'" :key="'itemform'+k" class="d-flex align-items-center">
                        Форма: <router-link :to="'/forms/formedit/'+itemForm.id">{{itemForm.name}}</router-link>
                        <ion-icon name="close-outline" style="color: red;" @click="deleteForm(itemForm.id)"></ion-icon>
                        <ion-icon class="drag-icon pr-3" name="ellipsis-vertical" style="float:right"></ion-icon>
                    </div>
                    </draggable>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import Spin from '../../spinner/SpinComponent';
import Message from '../../alert/MessageComponent';
import draggable from 'vuedraggable';

export default {
    name: 'form-section-list',
    components: {Spin, Message, draggable},
    data() {
        return {
            succes: false,
            loading: true,
            succesMessage: '',
            data: [],
        }
    },

    computed: {
        sortSections(){
            var array = []
            this.write(this.data, array, 0)
            return array
        }
    },

    methods: {

        inputChanged(value) {
            console.log(value)
            // var oldIndex = value.oldIndex
            // var newIndex = value.newIndex

            // var data = {
            //     active: {
            //         id: this.properties[newIndex].id,
            //     },
            //     second: {
            //         id: this.properties[oldIndex].id,
            //     }
            // }
            // this.changeSort(data)
        },

        changeSort(obj) {
            this.loading = true
            axios.patch('/api/services/sort/forms', obj)
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


        deleteSection(id) {
            var res =  confirm('Удалить этот раздел?')
            if(res) {
                this.data.forEach(koren => {
                    koren.childrens.forEach( (razdel,r) => {
                        if(razdel.id == id) {
                            koren.childrens.splice(r,1)
                        }
                    })
                })
                axios.delete('/api/forms/sections/'+id)
                .then(res => {

                }).catch( error => {

                }).finally( () => {

                })
            }
        },

        deleteForm(id) {
            var res = confirm('Удалить эту фому?')
            if(res) {
                this.data.forEach( (koren,k) => {
                    koren.childrens.forEach( (razdel, r) => {
                        razdel.form.forEach( (form,f) => {
                            if(form.id == id)
                                razdel.form.splice(f,1)
                        })
                    })
                })
                axios.delete('/api/forms/formdelete/'+id)
                .then(res => {

                }).catch( error => {

                }).finally( () => {

                })
            }
        },

        write(data, res, otstup) {
            for(var i in data){
                data[i].otstup = otstup
                res.push(data[i])
                if(data[i].childrens)
                    this.write(data[i].childrens, res, otstup+50)
            }
        },

        loadData(){
            axios('/api/forms/sections')
            .then(res => {
                if(res.data.status == 1)
                    this.data = res.data.data;
                else {
                    this.succes = true;
                    this.succesMessage = res.data.message;
                }
            }).catch(errors => {

            }).finally( () => {
                this.loading =false
            })
        }
    },
    mounted() {
        this.loadData()
    }
}
</script>

<style scoped>
#form-section-list{
    font-size: 1.2rem;
}
#form-section-list .icon{
    border-radius: 100%;
    padding: 5px;
    margin: 5px;
}
#form-section-list .hover{
    border-bottom: 1px solid #ddd;
}
#form-section-list .hover:hover{
    background: #ddd;
}
</style>
