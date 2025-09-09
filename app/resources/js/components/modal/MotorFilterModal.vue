<template>
<b-modal ref="motor-filter-modal" size="lg" hide-footer :title="'Поиск агрегатов'">
        <slot name="body">
            <div class="v-modal-content">
                <div class="row">
                    <div class="col-6">
                        <text-input v-model="search.name" :label="'Модель ДВС'"></text-input>
                        <brand-select v-model="search.brand_id"></brand-select>
                        <MotorType v-model="search.motor_type_id"></MotorType>

                    </div>

                    <div class="col-6">
                        <TransmissionType v-model="search.motor_transmission_id"></TransmissionType>
                        <DriverType v-model="search.motor_driver_id"></DriverType>
                        <MotorToxic v-model="search.motor_toxic_id"></MotorToxic>
                    </div>
                </div>
            </div>
        </slot>
        <slot name="footer">
            <div class="v-modal-footer">
                <div class="row">
                    <div class="col-6"></div>

                    <div class="col-3">
                        <button class="btn btn-primary btn-block" @click="acceptFilter">
                            Найти
                        </button>
                    </div>

                    <div class="col-3">
                        <button class=" btn btn-danger btn-block" @click="clearFilter">
                            Очистить
                        </button>
                    </div>
                </div>
            </div>
        </slot>
</b-modal>
</template>

<script>

import TextInput from '../html/TextInput';
import BrandSelect from '../html/BrandSelect';

import MotorType from '../html/MotorTypeSelect.vue';
import TransmissionType from '../html/MotorTransmissionSelect.vue';
import DriverType from '../html/MotorDriverSelect.vue';
import MotorToxic from '../html/Select/ToxicSelect';

export default {
    name: 'motor-filter-modal',
    components: {TextInput,BrandSelect,TransmissionType,DriverType,MotorType,MotorToxic},
    data() {
        return {
            show: false,
            search: {
                name: '',
                brand_id: 0,
                motor_transmission_id: 0,
                motor_driver_id: 0,
                motor_type_id: 0,
                motor_toxic_id: 0
            },
        }
    },
    methods: {
        closeModal: function () {
            this.$refs['motor-filter-modal'].hide()
        },
        acceptFilter() {
            this.closeModal()
            this.$emit('updateParent', this.search)
        },
        clearFilter() {
            this.closeModal()
            this.search = {}
            this.search.name = '',
            this.search.brand_id = 0
            this.search.motor_transmission_id = 0
            this.search.motor_driver_id = 0
            this.search.motor_type_id = 0
            this.search.motor_toxic_id = 0
            this.$emit('updateParent', this.search);
        }
    }
}
</script>

<style scoped lang="scss">
    .item-color{
       cursor: pointer;
       border-radius: 10px;
       background: #ddd;
    }
    .v-modal-shadow {
        position: fixed;
        top: 0;
        left: 0;
        min-height: 100%;
        width: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;;
    }

    .v-modal {
        background: #fff;
        border-radius: 8px;
        padding: 15px;
        min-width: 1000px;
        max-width: 1000px;
        position: absolute;
        top: 200px;
        left: 50%;
        transform: translate(-50%, -50%);

        &-close {
            border-radius: 50%;
            color: #fff;
            background: #2a4cc7;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: 7px;
            right: 7px;
            width: 30px;
            height: 30px;
            cursor: pointer;
        }

        &-title {
            color: #0971c7;
        }

        &-content {
            margin-bottom: 20px
        }

        &-footer {
            &__button {
                background-color: #0971c7;
                color: #fff;
                border: none;
                text-align: center;
                padding: 8px;
                font-size: 17px;
                font-weight: 500;
                border-radius: 8px;
                min-width: 150px;
            }
        }
    }
</style>

