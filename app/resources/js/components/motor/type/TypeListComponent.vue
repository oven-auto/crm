<template>
    <div id="motor-type-list">

        <message v-if="succes" :message="succesMessage"></message>

        <div class="row pb-3 d-flex align-items-center">
            <div class="col">
                <div class="h-title">Список типов моторов</div>
            </div>
            <div class="col text-right">
                <router-link class="btn btn-primary" :to="'/motortypes/create'">Добавить новый тип мотора</router-link>
            </div>
        </div>

        <spin v-if="loading"></spin>

        <table v-else class="table table-hover">
            <thead class="thead-dark">
            <tr>
                <th style="width: 80px;">#{{types.length}}</th>
                <th>Название</th>
                <th>Абревиатура</th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="type in types">
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
    name: 'motor-type-list',
    components: {
        Spin,
        Message
    },
    data() {
        return {
            types: [],
            loading: true,
            toEdit: '/motortypes/edit/',
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
            list(this, '/api/motortypes', 'types', 'message')
        }
    }
}
</script>
