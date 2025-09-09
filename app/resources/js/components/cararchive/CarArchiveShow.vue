<template>
<div class="color-edit">

        <spin v-if="loading && urlId"></spin>

        <div v-else class="row">
            <div class="col-12">
                <div class="">
                    {{car.brand.name}}
                    {{car.mark.name}}
                </div>

                <div class="">
                    {{car.complectation.name}}
                    {{car.complectation.code}}
                    {{car.complectation.motor.size}} {{car.complectation.motor.type.acronym}} ({{car.complectation.motor.power}}л.с.)
                    {{car.complectation.motor.transmission.acronym}}
                    {{car.complectation.motor.driver.acronym}}
                </div>

                <div class="">
                    {{car.vin}}
                </div>

                <div class="">
                    {{car.year}}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <a class="btn btn-secondary" @click="$router.go(-1)">Назад</a>
            </div>
        </div>
</div>
</template>

<script>
import Error from '../alert/ErrorComponent';
import Message from '../alert/MessageComponent';
import Spin from '../spinner/SpinComponent';

export default {
    name: 'car-show',
    components: {
        Error, Message, Spin,
    },
    data() {
        return {
            car: {
                brand:{},
                mark:{},
                complectation: {
                    motor:{
                        size: '',
                        power: '',
                        transmission:{},
                        driver: {},
                        type:{}
                    }
                },
            },
            urlId: this.$route.params.id,
            loading: true,
            notFound: false,
            succes: false,
            succesMessage: null,
        }
    },

    mounted() {
        this.loadData()
    },

    methods: {
        loadData() {
            edit(this, '/api/cars/' + this.urlId, 'car', 'message')
        }
    }
}
</script>
