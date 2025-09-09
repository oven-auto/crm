<template>
    <div id="shortcut-list">

        <message v-if="succes" :message="succesMessage"></message>

        <div class="row pb-3">
            <div class="col">
                <div class="h5">Страницы сайта</div>
            </div>
            <div class="col text-right">
                <router-link class="btn btn-primary" :to="'/pages/create'">Добавить новую страницу</router-link>
            </div>
        </div>

        <spin v-if="loading"></spin>

        <table v-else class="table">
            <thead class="table-dark">
                <tr>
                    <th style="width: 80px;">#{{data.length}}</th>
                    <th>Раздел</th>
                    <th>Название</th>
                    <th></th>
                </tr>
            </thead>

            <tr v-for="(item,i) in data" :key="'page_index'+i">
                <td>
                    <router-link :to="toEdit + item.id">
                        Open
                    </router-link>
                </td>
                <td>{{item.section.name }}</td>
                <td>{{item.title }}</td>
                <td class="text-right"><i class="fa fa-close text-danger" @click="deleteObj(item, i)"></i></td>
            </tr>
        </table>
    </div>
</template>

<script>

import Spin from '../spinner/SpinComponent';
import Message from '../alert/MessageComponent';

export default {
    name: 'shortcut-list',
    components: {
        Spin,
        Message
    },
    data() {
        return {
            data: [],
            loading: true,
            toEdit: '/pages/edit/',
            notFound: false,
            succes: false,
            succesMessage: null,
        }
    },
    mounted() {
        this.loadData()
    },
    methods: {
        deleteObj(obj, index) {
            var status = confirm('Вы действительно хотите удалить эту строку?')
            if(status) {
                axios.delete('/api/pages/'+obj.id)
                .then((res)=>{
                    this.data.splice(index,1)
                    makeToast(this,res.data.message)
                }).catch((errors) => {

                }).finally(() => {

                })
            }
        },
        loadData() {
            axios.get('/api/pages')
            .then(res => {
                if(res.data.status == 1)
                    this.data = res.data.data;
                else
                    this.data = []
                this.succesMessage = res.data.message;
                makeToast(this,this.succesMessage)
            })
            .catch(errors => {
                console.log(errors.message)
                this.succesMessage = errors.message;
                makeToast(this,this.succesMessage)
            })
            .finally(()=>{
                this.loading = false;
            })
        }
    }
}
</script>
