<template>
<b-modal ref="color-filter-modal" size="lg" hide-footer :title="'Поиск цветов'">
        <slot name="body">
            <div class="v-modal-content">
                <div class="row">
                    <div class="col-6">
                        <text-input v-model="search.code" :label="'Код'"></text-input>
                        <text-input v-model="search.name" :label="'Название'"></text-input>
                    </div>

                    <div class="col-6">
                        <brand-select v-model="search.brand_id"></brand-select>
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
</b-modal>
</template>

<script>

import TextInput from '../html/TextInput';
import BrandSelect from '../html/BrandSelect';
import MarkSelect from '../html/MarkSelect';

export default {
    name: 'color-filter-modal',
    components: {TextInput,BrandSelect},
    data() {
        return {
            show: false,
            search: {
                code: '',
                brand_id: 0,
                name: '',
            },
        }
    },
    methods: {
        closeModal: function () {
            this.$refs['color-filter-modal'].hide()
        },
        acceptFilter() {
            this.closeModal()
            this.$emit('updateParent', this.search)
        },
        clearFilter() {
            this.closeModal()
            this.search.code = '';
            this.search.brand_id = 0;
            this.search.name = '';
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

