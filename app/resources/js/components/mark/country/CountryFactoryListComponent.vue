<template>
<div id="country-factory-list">
    <div class="row pb-3 d-flex align-items-center">
        <div class="col">
            <div class="h-title">Список производителей</div>
        </div>
        <div class="col text-right">
            <router-link class="btn btn-primary" :to="'/countryfactories/create'">Добавить нового производителя</router-link>
        </div>
    </div>

    <spin v-if="loading"></spin>

    <table v-else class="table table-hover">
        <thead class="thead-dark">
        <tr>
            <th style="width: 80px;">#</th>
            <th>Дистрибьютор</th>
            <th>Страна производства</th>
            <th>Город производства</th>
            <th>Логистический центр</th>
        </tr>
        </thead>

        <tbody>
        <tr v-for="countryfactory in countryfactories">
            <td>
                <router-link :to="toEdit + countryfactory.id">
                    Open
                </router-link>
            </td>
            <td>{{ countryfactory.distributor }}</td>
            <td>{{ countryfactory.country }}</td>
            <td>{{  countryfactory.city }}</td>
            <td>{{ countryfactory.logistic }}</td>
        </tr>
        </tbody>
    </table>
</div>
</template>

<script>
import Spin from '../../spinner/SpinComponent';

export default {
    name: 'country-factory-list',
    components: {
        Spin
    },
    data() {
        return {
            toEdit: '/countryfactories/edit/',
            loading: true,
            countryfactories: [],
            notFound: false,
        }
    },
    mounted() {
        this.loadData()
    },
    methods: {
        loadData() {
            list(this, '/api/countryfactories', 'countryfactories', 'message')
        }
    }
}
</script>
