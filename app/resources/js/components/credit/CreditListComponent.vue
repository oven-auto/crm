<template>
    <div id="credit-list">

        <message v-if="succes" :message="succesMessage"></message>

        <div class="row pb-3">
            <div class="col">
                <div class="h5">Кредиты</div>
            </div>
            <div class="col text-right">
                <router-link class="btn btn-primary" :to="'/credits/create'">Добавить новый кредит</router-link>
            </div>
        </div>

        <spin v-if="loading"></spin>

        <table v-else class="table table-hover">
            <thead class="thead-dark">
                <tr>
                    <th style="width: 80px;">#</th>
                    <th>Название</th>
                    <th>Модели</th>
                    <th>Ставка %</th>
                    <th>Срок (лет)</th>
                    <th>ПВ (%)</th>
                    <th>Платеж (руб.)</th>
                    <th>Начало</th>
                    <th>Конец</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
            <tr v-for="item in data">
                <td>
                    <router-link :to="toEdit + item.id">
                        Open
                    </router-link>
                </td>
                <td>{{item.name }}</td>
                <td>
                    <span v-for="mark in item.marks" class="badge badge-dark ml-1">
                        {{mark.name}}
                    </span>
                </td>
                <td>{{item.rate}}</td>
                <td>{{item.period}}</td>
                <td>{{item.contribution}}</td>
                <td>{{item.pay}}</td>
                <td>{{item.begin_at}}</td>
                <td>{{item.end_at}}</td>
                <td class="text-right status-badge">
                    <div v-if="item.status == 1">
                        <span v-if="moreThanCurrentDate(item.begin_at)" class="badge badge-primary">
                            Ожидает
                        </span>
                        <span v-else-if="lessThanCurrentDate(item.end_at)" class="badge badge-danger">
                            Просрочен
                        </span>
                        <span v-else class="badge badge-success">
                            Актуально
                        </span>
                    </div>
                    <span v-else class="badge badge-secondary">
                        Отключен
                    </span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>

import Spin from '../spinner/SpinComponent';
import Message from '../alert/MessageComponent';

export default {
    name: 'credit-list',
    components: {
        Spin,
        Message
    },
    data() {
        return {
            data: [],
            loading: true,
            toEdit: '/credits/edit/',
            notFound: false,
            succes: false,
            succesMessage: null,
        }
    },
    mounted() {
        this.loadData()
    },
    methods: {
        moreThanCurrentDate(param) {
            return Date.parse(param) > Date.now()
        },
        lessThanCurrentDate(param) {
            console.log(Date.parse(param))
            console.log(Date.now())
            console.log('---')
            return Date.parse(param) < Date.now()
        },
        loadData() {
            axios.get('/api/credits')
            .then(res => {
                if(res.data.status == 1)
                    this.data = res.data.data;
                else {
                    this.succes = true;
                    this.succesMessage = res.data.message;
                }
                this.loading = false;
            })
            .catch(errors => {
                console.log(errors)
            })
        }
    }
}
</script>

<style scoped>
#credit-list td{
    vertical-align: middle;
}
#credit-list .status-badge .badge{
    font-weight: normal;
    width: 70px;

}
</style>
