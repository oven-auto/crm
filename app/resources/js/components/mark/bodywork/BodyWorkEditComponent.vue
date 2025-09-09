<template>
<div class="body-work-edit">


    <spin v-if="loading && urlId"></spin>


    <div v-else>
        <form>
            <div class="h5">{{ bodywork.name ? bodywork.name : 'Новый тип кузова' }}</div>

            <div class="row pb-3">
                <div class="col-6">
                    <div >
                        <label for="name">Название</label>
                        <input type="text" name="name" v-model="bodywork.name" class="form-control"/>
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
    name: 'body-work-edit',
    components: {
        Error, Message, Spin
    },
    data() {
        return {
            bodywork: {
                name: null,
            },
            loading: true,
            urlId: this.$route.params.id,
            message: null,
        }
    },
    mounted() {
        if(this.urlId)
            this.loadData(this.urlId)
    },
    methods: {
        loadData(id) {
            edit(this, '/api/bodyworks/' + this.urlId + '/edit', 'bodywork', 'message')
        },

        updateData(id) {
            update(this, '/api/bodyworks/' + this.urlId, this.getFormData('patch'), 'bodywork', 'message')
        },

        storeData() {
            storage(this, '/api/bodyworks/', this.getFormData(), 'bodywork', 'message', 'urlId', 'bodyworks')
        },

        getFormData(method = '') {
            var formData = new FormData();

            formData.append('name', this.bodywork.name);

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
