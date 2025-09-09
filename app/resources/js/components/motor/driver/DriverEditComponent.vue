<template>
    <div class="motor-type-edit">

        <spin v-if="loading && urlId"></spin>

        <div v-else>
            <form>
                <div class="h5">{{ driver.name ? driver.name : 'Новый тип привода' }}</div>

                <div class="row pb-3">
                    <div class="col-6">
                        <div >
                            <label for="name">Название</label>
                            <input type="text" name="name" v-model="driver.name" class="form-control"/>
                        </div>

                        <div >
                            <label for="name">Абревиатура</label>
                            <input type="text" name="name" v-model="driver.acronym" class="form-control"/>
                        </div>

                        <DriverType v-model="driver.driver_type_id"></DriverType>
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
import DriverType from '../../html/DriverTypeSelect';

export default {
    name: 'device-driver-edit',
    components: {
        Error, Message, Spin, DriverType
    },
    data() {
        return {
            driver: {
                name: '',
                acronym: '',
                driver_type_id: '',
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
            edit(this, '/api/motordrivers/' + id + '/edit', 'driver', 'message')
        },

        updateData(id) {
            update(this, '/api/motordrivers/' + id, this.getFormData('patch'), 'driver', 'message')
        },

        storeData() {
            storage(this, '/api/motordrivers/', this.getFormData(), 'driver', 'message', 'urlId', 'motordrivers')
        },

        getFormData(method = '') {
            var formData = new FormData();

            formData.append('name', this.driver.name);
            formData.append('acronym', this.driver.acronym==null?'':this.driver.acronym);
            formData.append('driver_type_id', this.driver.driver_type_id);

            if(method == 'patch')
                formData.append("_method", "PATCH");

            return formData;
        },
    }
}
</script>
