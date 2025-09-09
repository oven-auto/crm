<template>
<div class="country-factory-edit">


    <spin v-if="loading && urlId"></spin>

    <div v-else>
        <form>
            <div class="h5">{{ countryfactory.country ? countryfactory.country : 'Новое происхождение' }}</div>

            <div class="row pb-3">
                <div class="col-6">
                    <div >
                        <label for="name">Страна</label>
                        <input type="text" name="name" v-model="countryfactory.country" class="form-control"/>
                    </div>

                    <div >
                        <label for="name">Город</label>
                        <input type="text" name="name" v-model="countryfactory.city" class="form-control"/>
                    </div>
                </div>

                <div class="col-6">
                    <div >
                        <label for="name">Дистрибьютор</label>
                        <input type="text" name="name" v-model="countryfactory.distributor" class="form-control"/>
                    </div>

                    <div >
                        <label for="name">Логистический центр</label>
                        <input type="text" name="name" v-model="countryfactory.logistic" class="form-control"/>
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
    name: 'country-factory-edit',
    components: {
        Error, Message, Spin
    },
    data() {
        return {
            countryfactory: {

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
            edit(this, '/api/countryfactories/' + this.urlId + '/edit', 'countryfactory', 'message')
        },

        updateData(id) {
            update(this, '/api/countryfactories/' + this.urlId, this.getFormData('patch'), 'countryfactory', 'message')
        },

        storeData() {
            storage(this, '/api/countryfactories/', this.getFormData(), 'countryfactory', 'message', 'urlId', 'countryfactories')
        },

        getFormData(method = '') {
            var formData = new FormData();

            formData.append('country', this.countryfactory.country);
            formData.append('city', this.countryfactory.city);
            formData.append('distributor', this.countryfactory.distributor);
            formData.append('logistic', this.countryfactory.logistic);

            if(method == 'patch')
                formData.append("_method", "PATCH");

            return formData;
        },
    }
}
</script>
