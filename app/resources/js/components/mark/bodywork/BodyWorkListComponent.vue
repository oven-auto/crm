<template>
<div id="property-list">
    <div class="row pb-3 d-flex align-items-center">
        <div class="col">
            <div class="h-title">Список типов кузовов</div>
        </div>
        <div class="col text-right">
            <router-link class="btn btn-primary" :to="'/bodyworks/create'">Добавить новый кузов</router-link>
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

        <tbody>
        <tr v-for="bodywork in bodyworks">
            <td>
                <router-link :to="toEdit + bodywork.id">
                    Open
                </router-link>
            </td>
            <td>{{ bodywork.name }}</td>
        </tr>
        </tbody>
    </table>
</div>
</template>

<script>
import Spin from '../../spinner/SpinComponent';

export default {
    name: 'body-work-list',
    components: {
        Spin
    },
    data() {
        return {
            toEdit: '/bodyworks/edit/',
            loading: true,
            bodyworks: [],
            message: ''
        }
    },
    mounted() {
        this.loadData()
    },
    methods: {
        loadData() {
            list(this, '/api/bodyworks', 'bodyworks', 'message')
        }
    }
}
</script>
