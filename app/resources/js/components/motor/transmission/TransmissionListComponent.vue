<template>
    <div id="motor-type-list">

        <message v-if="succes" :message="succesMessage"></message>

        <div class="row pb-3 d-flex align-items-center">
            <div class="col">
                <div class="h-title">Список типов трансмиссий</div>
            </div>
            <div class="col text-right">
                <router-link class="btn btn-primary" :to="'/motortransmissions/create'">Добавить новый тип трансмиссии</router-link>
            </div>
        </div>

        <spin v-if="loading"></spin>

        <table v-else class="table table-hover">
            <thead class="thead-dark">
            <tr>
                <th style="width: 80px;">#{{transmissions.length}}</th>
                <th>Название</th>
                <th>Абревиатура</th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="type in transmissions">
                <td>
                    <router-link :to="toEdit + type.id">
                        Open
                    </router-link>
                </td>
                <td>{{ type.name }}</td>
                <td>{{ type.acronym }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>

import Spin from '../../spinner/SpinComponent';
import Message from '../../alert/MessageComponent';

export default {
    name: 'motor-transmission-list',
    components: {
        Spin,
        Message
    },
    data() {
        return {
            transmissions: [],
            loading: true,
            toEdit: '/motortransmissions/edit/',
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
            list(this, '/api/motortransmissions', 'transmissions', 'message')
        }
    }
}
</script>
