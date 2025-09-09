<template>
<b-modal ref="color-list-modal" size="lg" hide-footer :title="'Список цветов'">
            <slot name="body">
                <div class="v-modal-content">
                    <div class="row">
                        <div class="col-2 mb-3 text-center" v-for="item in data">
                            <div style="height: 100%;" class="item-color d-flex flex-column"
                                @click="checkColor(item)"
                                :class="{'active-color': colors.find(col => col.id == item.id)}">
                                <div>
                                    {{item.code}}
                                </div>
                                <ColorIcon :colors="item.web" style="margin:auto;"></ColorIcon>
                                <div>
                                    <small class="text-muted">
                                        {{item.name}}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </slot>
            <slot name="footer">
                <div class="v-modal-footer">
                    <button class="v-modal-footer__button" @click="closeModal">
                        Ок
                    </button>
                </div>
            </slot>
</b-modal>
</template>

<script>
    import ColorIcon from '../html/ColorIcon';

    export default {
        name: "color-list-modal",
        components: {
            ColorIcon
        },
        data: function () {
            return {
                show: false,
                data: [],
                brand: 0,
                colors: []
            }
        },
        mounted() {
            this.loadData()
        },
        methods: {
            //
            checkColor(param) {
                this.$emit('updateParent', {
                    id: param.id,
                    name: param.name,
                    code: param.code,
                    web: param.web,
                    img: ''
                })
            },

            closeModal: function () {
                this.$refs['color-list-modal'].hide()
            },

            loadData() {
                var getParam = '';

                if(this.brand!='' && this.brand > 0)
                    getParam+='?brand_id='+this.brand;

                axios.get('/api/colors'+getParam)
                .then(res => {
                    this.data = res.data.data
                })
                .catch(error => {
                    console.log(error)
                })
            }
        }
    }
</script>

<style scoped lang="scss">
    .item-color{
       cursor: pointer;
       border-radius: 10px;
       border: 1px solid #eee;
    }
    .active-color{
        background: #ddd;
    }
    .v-modal-shadow {
        position: fixed;
        top: 0;
        left: 0;
        min-height: 100vh;
        width: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;;
    }

    .v-modal {
        background: #fff;
        overflow-y: scroll;
        border-radius: 8px;
        padding: 15px;
        min-width: 1000px;
        max-width: 1000px;
        position: absolute;
        max-height: 80vh;
        top: 400px;
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
