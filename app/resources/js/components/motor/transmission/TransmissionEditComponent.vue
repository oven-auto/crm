<template>
    <div class="motor-type-edit">

        <spin v-if="loading && urlId"></spin>

        <div v-else>
            <form>
                <div class="h5">{{ transmission.name ? transmission.name : 'Новый тип трансмиссии' }}</div>

                <div class="row pb-3">
                    <div class="col-6">
                        <div >
                            <label for="name">Название</label>
                            <input type="text" name="name" v-model="transmission.name" class="form-control"/>
                        </div>

                        <div >
                            <label for="name">Абревиатура</label>
                            <input type="text" name="name" v-model="transmission.acronym" class="form-control"/>
                        </div>

                        <div>
                            <TransmissionType v-model="transmission.transmission_type_id"></TransmissionType>
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
import TransmissionType from '../../html/TransmissionTypeSelect';

export default {
    name: 'device-type-edit',
    components: {
        Error, Message, Spin, TransmissionType
    },
    data() {
        return {
            transmission: {
                name: '',
                acronym: '',
                type: ''
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
            edit(this, '/api/motortransmissions/' + id + '/edit', 'transmission', 'message')
        },

        updateData(id) {
            update(this, '/api/motortransmissions/' + id, this.getFormData('patch'), 'transmission', 'message')
        },

        storeData() {
            storage(this, '/api/motortransmissions/', this.getFormData(), 'transmission', 'message', 'urlId', 'motortransmissions')
        },

        getFormData(method = '') {
            var formData = new FormData();

            formData.append('name', this.transmission.name);
            formData.append('acronym', this.transmission.acronym==null?'':this.transmission.acronym);
            formData.append('transmission_type_id', this.transmission.transmission_type_id);

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
