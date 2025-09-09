<template>
<div class="device-type-edit">


    <spin v-if="loading && urlId"></spin>

    <div v-else>
        <form>
            <div class="h5">{{ property.name ? property.name : 'Новый тип оборудования' }}</div>

            <div class="row pb-3">
                <div class="col-6">
                    <div >
                        <label for="name">Название</label>
                        <input type="text" name="name" v-model="property.name" class="form-control"/>
                    </div>
                </div>
            </div>

        </form>

        <FormControll :id="urlId"></FormControll>

    </div>
</div>
</template>

<script>
import Error from '../alert/ErrorComponent';
import Message from '../alert/MessageComponent';
import Spin from '../spinner/SpinComponent';

export default {
    name: 'device-type-edit',
    components: {
        Error, Message, Spin
    },
    data() {
        return {
            property: {
                name: null,
            },
            loading: true,
            urlId: this.$route.params.id,
            message: null,
        }
    },
    mounted() {
        if(this.urlId)
            this.loadProperty(this.urlId)
    },
    methods: {
        loadProperty(id) {
            edit(this, '/api/properties/' + this.urlId + '/edit', 'property', 'message')
        },

        updateData(id) {
            update(this, '/api/properties/' + this.urlId, this.getFormData('patch'), 'property', 'message')
        },

        storeData() {
            storage(this, '/api/properties/', this.getFormData(), 'property', 'message', 'urlId', 'properties')
        },

        getFormData(method = '') {
            var formData = new FormData();

            formData.append('name', this.property.name);

            if(method == 'patch')
                formData.append("_method", "PATCH");

            return formData;
        },
    }
}
</script>
