<template>
<div class="device-type-edit">


    <spin v-if="loading && urlId"></spin>

    <error v-if="notFound"></error>

    <div v-else>
        <form>
            <div class="h5">{{ type.name ? type.name : 'Новый тип оборудования' }}</div>

            <div class="row pb-3">
                <div class="col-6">
                    <div >
                        <label for="name">Название</label>
                        <input type="text" name="name" v-model="type.name" class="form-control"/>
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
    name: 'device-type-edit',
    components: {
        Error, Message, Spin
    },
    data() {
        return {
            type: {
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
            this.loadType(this.urlId)
    },
    methods: {
        loadType(id) {
            edit(this, '/api/devicetypes/' + this.urlId + '/edit', 'type', 'message')
        },

        updateData(id) {
            update(this, '/api/devicetypes/' + this.urlId, this.getFormData('patch'), 'type', 'message')
        },

        storeData() {
            storage(this, '/api/devicetypes/', this.getFormData(), 'type', 'message', 'urlId', 'devicetypes')
        },

        getFormData(method = '') {
            var formData = new FormData();

            formData.append('name', this.type.name);

            if(method == 'patch')
                formData.append("_method", "PATCH");

            return formData;
        },

        getConfig() {
            return {
                'content-type': 'multipart/form-data'
            }
        },
    }
}
</script>
