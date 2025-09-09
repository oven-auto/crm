<template>
    <div class="motor-type-edit">

        <spin v-if="loading && urlId"></spin>

        <div v-else>
            <form>
                <div class="h5">{{ type.name ? type.name : 'Новый тип оборудования' }}</div>

                <div class="row pb-3">
                    <div class="col-6">
                        <div >
                            <label for="name">Название</label>
                            <input type="text" name="name" v-model="type.name" class="form-control"/>
                        </div>

                        <div >
                            <label for="name">Абревиатура</label>
                            <input type="text" name="name" v-model="type.acronym" class="form-control"/>
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
                name: '',
                acronym: '',
            },
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
            edit(this, '/api/motortypes/' + id + '/edit', 'type', 'message')
        },

        updateData(id) {
            update(this, '/api/motortypes/' + id, this.getFormData('patch'), 'type', 'message')
        },

        storeData() {
            storage(this, '/api/motortypes/', this.getFormData(), 'type', 'message', 'urlId', 'motortypes')
        },

        getFormData(method = '') {
            var formData = new FormData();

            formData.append('name', this.type.name);
            formData.append('acronym', this.type.acronym==null?'':this.type.acronym);

            if(method == 'patch')
                formData.append("_method", "PATCH");

            return formData;
        },
    },
}
</script>
