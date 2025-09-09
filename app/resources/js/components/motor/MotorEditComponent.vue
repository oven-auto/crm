<template>
    <div class="motor-type-edit">

        <spin v-if="loading && urlId"></spin>

        <div v-else>
            <form>
                <div class="h5">{{ motor.name ? motor.name : 'Новый тип мотора' }}</div>

                <div class="row pb-3">
                    <div class="col-6">

                        <BrandSelect
                            :name="'brand_id'"
                            v-model="motor.brand_id"
                        >
                        </BrandSelect>

                        <VInput :label="'Модель двигателя'" v-model="motor.name"></VInput>

                        <MotorTypeSelect
                            :name="'motor_type_id'"
                            :label="'Тип двигателя'"
                            v-model="motor.motor_type_id"
                        >
                        </MotorTypeSelect>

                        <div >
                            <label for="power">Мощность: {{ motor.power }}</label>
                            <input
                                type="range"
                                name="power"
                                v-model="motor.power"
                                class="form-control"
                                min="0"
                                max="300"
                                step="1"
                            />
                        </div>

                        <div >
                            <label for="size">Объем: {{ motor.size }}</label>
                            <input
                                type="range"
                                name="size"
                                v-model="motor.size"
                                class="form-control"
                                min="0"
                                max="5"
                                step="0.1"
                            />
                        </div>

                        <div >
                            <label for="valve">Кол-во клапанов: {{ motor.valve }}</label>
                            <input
                                type="range"
                                name="valve"
                                v-model="motor.valve"
                                class="form-control"
                                min="8"
                                max="32"
                                step="8"
                            />
                        </div>

                        <MotorToxic
                            :name="'motor_toxic_id'"
                            v-model="motor.motor_toxic_id"
                        >
                        </MotorToxic>
                    </div>

                    <div class="col-6">

                        <VInput :label="'Модель КПП'" v-model="motor.transmission_name"></VInput>


                        <MotorTransmissionSelect
                            :name="'motor_transmission_id'"
                            v-model="motor.motor_transmission_id"
                        >
                        </MotorTransmissionSelect>

                        <MotorDriverSelect
                            :name="'motor_driver_id'"
                            v-model="motor.motor_driver_id"
                        >
                        </MotorDriverSelect>


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
import BrandSelect from '../html/BrandSelect.vue';
import MotorTypeSelect from '../html/MotorTypeSelect.vue';
import MotorTransmissionSelect from '../html/MotorTransmissionSelect.vue';
import MotorDriverSelect from '../html/MotorDriverSelect.vue';
import MotorToxic from '../html/Select/ToxicSelect';
import VInput from '../html/TextInput';

export default {
    name: 'motor-edit',
    components: {
        Error, Message, Spin, BrandSelect, MotorTypeSelect,MotorTransmissionSelect,MotorDriverSelect,MotorToxic,VInput
    },
    data() {
        return {
            motor: {
                name: '',
                brand_id: 0,
                motor_transmission_id: 0,
                motor_driver_id: 0,
                motor_type_id: 0,
                motor_toxic_id: '',
                size: 0,
                power: 0,
                valve: 0,
                transmission_name: ''
            },

            loading: true,
            urlId: this.$route.params.id,
            message: null,
            previusPage: '/'
        }
    },
    mounted() {
        this.previusPage = this.prevRoute.fullPath
        if(this.urlId)
            this.loadData();
    },

    beforeRouteEnter(to, from, next) {
        next(vm => {
            vm.prevRoute = from;
        });
    },

    methods: {
        loadData(id) {
            edit(this, '/api/motors/' + this.urlId + '/edit', 'motor', 'message')
        },

        updateData(id) {
            update(this, '/api/motors/' + this.urlId, this.getFormData('patch'), 'motor', 'message')
        },

        storeData() {
            storage(this, '/api/motors/', this.getFormData(), 'motor', 'message', 'urlId', 'motors')
        },

        getFormData(method = '') {
            var formData = new FormData();

            formData.append('name', this.motor.name);
            formData.append('power', this.motor.power);
            formData.append('size', this.motor.size);
            formData.append('valve', this.motor.valve);
            formData.append('brand_id', this.motor.brand_id);
            formData.append('motor_type_id', this.motor.motor_type_id);
            formData.append('motor_transmission_id', this.motor.motor_transmission_id);
            formData.append('motor_driver_id', this.motor.motor_driver_id);
            formData.append('motor_toxic_id', this.motor.motor_toxic_id);
            formData.append('transmission_name', this.motor.transmission_name ?? '');

            if(method == 'patch')
                formData.append("_method", "PATCH");

            return formData;
        },
    }
}
</script>
