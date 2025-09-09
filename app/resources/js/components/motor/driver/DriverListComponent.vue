<template>
    <div id="motor-type-list">

        <message v-if="succes" :message="succesMessage"></message>

        <div class="row pb-3 d-flex align-items-center">
            <div class="col">
                <div class="h-title">Список типов привода</div>
            </div>
            <div class="col text-right">
                <router-link class="btn btn-primary" :to="'/motordrivers/create'">Добавить новый тип привода</router-link>
            </div>
        </div>

        <spin v-if="loading"></spin>

        <table v-else class="table table-hover">
            <thead class="thead-dark">
            <tr>
                <th style="width: 80px;">#{{drivers.length}}</th>
                <th>Название</th>
                <th>Абревиатура</th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="item in drivers">
                <td>
                    <router-link :to="toEdit + item.id">
                        Open
                    </router-link>
                </td>
                <td>{{ item.name }}</td>
                <td>{{ item.acronym }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>

import Spin from '../../spinner/SpinComponent';
import Message from '../../alert/MessageComponent';

export default {
    name: 'motor-driver-list',
    components: {
        Spin,
        Message
    },
    data() {
        return {
            drivers: [],
            loading: true,
            toEdit: '/motordrivers/edit/',
            notFound: false,
            succes: false,
            message: null,
        }
    },
    mounted() {
        this.loadTypes()
    },
    methods: {
        loadTypes() {
            list(this, '/api/motordrivers', 'drivers', 'message')
        }
    }
}
</script>
