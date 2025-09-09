<template>
<div class="device-type-edit">


    <spin v-if="loading && urlId"></spin>

    <error v-if="notFound"></error>

    <div v-else>
        <form>
            <div class="h5">{{ filter.name ? filter.name : 'Новая группа оборудования' }}</div>

            <div class="row pb-3">
                <div class="col-6">
                    <div >
                        <label for="name">Название</label>
                        <input type="text" name="name" v-model="filter.name" class="form-control"/>
                    </div>


                </div>
            </div>

        </form>

        <FormControll :id="urlId"></FormControll>
    </div>
</div>
</template>

<script>

import Error from '../../alert/ErrorComponent';
import Message from '../../alert/MessageComponent';
import Spin from '../../spinner/SpinComponent';

export default {
    name: 'device-filter-edit',
    components: {
        Error, Message, Spin
    },
    data() {
        return {
            filter: {
                name: null,
                //icon: null,
            },
            //iconSrc: null,
            notFound: false,
            loading: true,
            urlId: this.$route.params.id,
            succes: false,
            message: null,
        }
    },
    mounted() {
        if(this.urlId)
            this.loadFilter(this.urlId)
    },
    methods: {
        loadFilter(id) {
            edit(this, '/api/devicefilters/' + this.urlId + '/edit', 'filter', 'message')
        },

        updateData(id) {
            update(this, '/api/devicefilters/' + this.urlId, this.getFormData('patch'), 'filter', 'message')
        },

        storeData() {
            storage(this, '/api/devicefilters/', this.getFormData(), 'filter', 'message', 'urlId', 'devicefilters')
        },

        getFormData(method = '') {
            var formData = new FormData();

            formData.append('name', this.filter.name);

            if(method == 'patch')
                formData.append("_method", "PATCH");

            return formData;
        },
    }
}
</script>
