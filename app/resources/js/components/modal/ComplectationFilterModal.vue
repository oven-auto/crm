<template>
    <div v-if="show" class="v-modal-shadow" @click.self="closeModal">
        <div class="v-modal">
            <div class="v-modal-close" @click="closeModal">&#10006;</div>
            <slot name="title">
                <h3 class="v-modal-title">Заголовок</h3>
            </slot>
            <slot name="body">
                <div class="v-modal-content">
                    <div class="row">
                        <div class="col-6">
                            <brand-select v-model="search.brand_id"></brand-select>
                            <text-input v-model="search.name" :label="'Название'"></text-input>
                            <text-input v-model="search.code" :label="'КОД'"></text-input>
                        </div>

                        <div class="col-6">
                            <MarkSelect v-model="search.mark_id" :actual="1" :title="'Актуальные модель'" :brand="search.brand_id"></MarkSelect>
                            <MarkSelect v-model="search.mark_id" :nonactual="1" :title="'Архивная модель'" :brand="search.brand_id"></MarkSelect>
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
    </div>
</template>

<script>
    import TextInput from '../html/TextInput';
    import BrandSelect from '../html/BrandSelect';
    import MarkSelect from '../html/MarkSelect';
    import ComplectationSelect from '../html/ComplectationSelect';

    export default {
        name: "ModalWindow",
        components: {
            TextInput, BrandSelect,MarkSelect,ComplectationSelect
        },
        data: function () {
            return {
                show: false,
                search: {
                    brand_id: 0,
                    mark_id: 0,
                    name: '',
                    code: '',
                    status: ''
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
                this.search.name = ''
                this.search.code = ''

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
            },
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
