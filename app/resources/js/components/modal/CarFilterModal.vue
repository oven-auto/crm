<template>
    <b-modal ref="car_filter_modal" size="lg" hide-footer :title="'Поиск клиента'">
        <div class="d-block text-left">
            <slot name="body">
                <div class="v-modal-content">
                    <div class="row">
                        <div class="col-6">
                            <brand-select v-model="search.brand_id"></brand-select>
                            <text-input v-model="search.vin" :label="'VIN'"></text-input>
                            <complectation-select v-model="search.complectation_id" :mark="search.mark_id"></complectation-select>
                            <DeliveryType v-model="search.delivery_type_id"></DeliveryType>
                            <DeliveryStage v-model="search.delivery_stage_id"></DeliveryStage>

                        </div>

                        <div class="col-6">
                            <MarkSelect v-model="search.mark_id" :actual="1" :title="'Актуальные модель'" :brand="search.brand_id"></MarkSelect>
                            <MarkSelect v-model="search.mark_id" :nonactual="1" :title="'Архивная модель'" :brand="search.brand_id"></MarkSelect>
                            <CheckBox class="pt-2" v-model="search.revaluation" :label="'Переоценка'"></CheckBox>
                        </div>
                    </div>
                </div>
            </slot>
            <slot name="footer">
                <div class="v-modal-footer">
                    <button class="btn btn-primary" @click="acceptFilter">
                        Найти
                    </button>

                    <button class=" btn btn-danger" @click="clearFilter">
                        Очистить
                    </button>
                </div>
            </slot>
        </div>
    </b-modal>
</template>

<script>
    import TextInput from '../html/TextInput';
    import BrandSelect from '../html/BrandSelect';
    import MarkSelect from '../html/MarkSelect';
    import ComplectationSelect from '../html/ComplectationSelect';
    import DeliveryType from '../html/Select/DeliveryTypeSelect';
    import DeliveryStage from '../html/Select/DeliveryStageSelect';
    import CheckBox from '../checkbox/CheckBox';

    export default {
        name: "ModalWindow",
        components: {
            TextInput, BrandSelect,MarkSelect,ComplectationSelect, DeliveryType, DeliveryStage,CheckBox
        },
        data: function () {
            return {
                show: false,
                search: {
                    brand_id: 0,
                    mark_id: 0,
                    complectation_id: 0,
                    vin: '',
                    delivery_type_id: 0,
                    delivery_stage_id: 0,
                    revaluation: 0
                }
            }
        },
        mounted() {

        },
        methods: {
            acceptFilter() {
                this.show = false
                this.$emit('updateParent', this.search)
            },
            clearFilter() {
                this.show = false
                this.search.brand_id = 0
                this.search.mark_id = 0
                this.search.complectation_id = 0
                this.search.vin = ''
                this.search.delivery_type_id = 0
                this.search.delivery_stage_id = 0
                this.search.revaluation = 0
                this.$emit('updateParent', this.search)
            },

            closeModal: function () {
                this.show = false
            },

            loadData() {

            }
        },
        watch: {
            'search.brand_id'(val) {
                this.search.mark_id = 0
                this.search.complectation_id = 0
            },
            'search.mark_id'(val) {
                this.search.complectation_id = 0
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
        top: 300px;
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
