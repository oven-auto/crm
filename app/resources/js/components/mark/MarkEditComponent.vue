<template>
<div class="mark-edit">

    <spin v-if="loading && urlId"></spin>

    <error v-if="notFound"></error>

    <div v-else>
        <form>
            <div class="h5">{{ mark.name ? 'Модель: ' + mark.name : 'Новая модель' }}</div>

            <MarkMainInfoVue :mark="mark"></MarkMainInfoVue>

            <MarkDocumentVue :mark="mark"></MarkDocumentVue>

            <MarkProperties :installed="mark.properties" @updateProperties="getProperties"></MarkProperties>

            <div class="row py-3 text-center">
                <div class="col-6 mb-3">
                        <div class="h5 text-left">
                            Иконка модели
                        </div>
                        <div v-if="mark.icon" class="pb-3">
                            <img :src="mark.icon" style="height: 200px;width:auto;">
                        </div>
                        <div v-else class="pb-3">
                            <img src="/images/somecar.png" style="height: 200px;width:auto;">
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="accessory" name="accessory"  @change="onAttachmentIcon">
                            <label class="custom-file-label" for="accessory">Выберите фаил</label>
                            <div class="invalid-feedback">Example invalid custom file feedback</div>
                        </div>
                </div>

                <div class="col-6 mb-3">
                        <div class="h5 text-left">
                            Банер модели
                        </div>
                        <div v-if="mark.banner" class="pb-3">
                            <img :src="mark.banner" style="height: 200px;width:auto;">
                        </div>
                        <div v-else class="pb-3">
                            <img src="/images/somecar.png" style="height: 200px;width:auto;">
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="accessory" name="accessory"  @change="onAttachmentBanner">
                            <label class="custom-file-label" for="accessory">Выберите фаил</label>
                            <div class="invalid-feedback">Example invalid custom file feedback</div>
                        </div>
                </div>
            </div>

            <div class="row pb-5">
                <div class="col-12 mb-3">
                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="h5">
                                Палитра
                            </div>
                        </div>
                        <div class="col-6 text-right">
                            <button type="button" class="btn btn-success" @click="showModal" >
                                Выбрать цвет
                            </button>
                        </div>
                    </div>

                    <div class="row mark-colors">
                        <div class="col-3 mb-3" v-for="itemColor in mark.colors">
                            <div class="item-color text-center border p-3 d-flex flex-column" style="height: 100%;">
                                <div>
                                    {{itemColor.code}}
                                </div>
                                <ColorIcon :colors="itemColor.web" style="margin:auto;"></ColorIcon>
                                <div>
                                    <small class="text-muted">
                                        {{itemColor.name}}
                                    </small>
                                </div>
                                <div class="">
                                    <div v-if="itemColor.img">
                                        <img :src="itemColor.img" style="width:100%;">
                                    </div>
                                    <div v-else class="pb-3">
                                        <img src="/images/somecar.png" style="">
                                    </div>
                                    <div class="custom-file ">
                                        <input type="file" class="custom-file-input" v-bind:id="itemColor.id"  @change="onAttachmentColor">
                                        <label class="custom-file-label" for="accessory">Выберите фаил</label>
                                        <div class="invalid-feedback">Example invalid custom file feedback</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </form>

        <FormControll :id="urlId"></FormControll>
    </div>

    <modal-window ref="color-list-modal" @updateParent="getDataModal"></modal-window>

</div>
</template>

<script>
import Error from '../alert/ErrorComponent';
import Message from '../alert/MessageComponent';
import Spin from '../spinner/SpinComponent';



import ModalWindow from '../modal/Modal';

import ColorIcon from '../html/ColorIcon';

import MarkMainInfoVue from './MarkMainInfo.vue';
import MarkDocumentVue from './MarkDocument.vue';
import MarkProperties from './MarkProperties.vue';



export default {
    name: 'mark-edit',
    components: {
        Error, Message, Spin, ModalWindow, ColorIcon, MarkMainInfoVue, MarkDocumentVue, MarkProperties
    },
    data() {
        return {
            show: true,
            mark: {
                name: '',
                status: 0,
                show_driver: 1,
                show_toxic: 1,
                prefix: '',
                brand_id: 0,
                body_work_id: 0,
                country_factory_id: 0,
                info: {
                    slogan: '',
                    description: '',
                },
                document: {
                    brochure: '',
                    accessory: '',
                    manual: '',
                    price: '',
                },
                properties: [],
                icon: '',
                banner: '',
                colors: []
            },
            //properties: [],
            notFound: false,
            loading: true,
            urlId: this.$route.params.id,
            succes: false,
            succesMessage: null,
        }
    },
    mounted() {
        if(this.urlId)
            this.loadData(this.urlId)
    },

    methods: {
        getProperties(data) {
            this.mark.properties = data
        },



        getDataModal(data) {
            var exsist = false;
            var index = false;

            if(this.mark.colors.length == 0)
                this.mark.colors.push(data);
            else{
                this.mark.colors.forEach( (item, i) => {
                    if(data.id == item.id){
                        exsist = true;
                        index = i;
                    }
                });
                if(exsist == false)
                    this.mark.colors.push(data);
                else {
                    this.mark.colors.splice(index,1)
                }
            }
        },

        showModal: function () {
            this.$refs['color-list-modal'].$refs['color-list-modal'].show()
            this.$refs['color-list-modal'].brand = this.mark.brand_id
            this.$refs['color-list-modal'].colors = this.mark.colors
            this.$refs['color-list-modal'].loadData()
        },

        //цепляем картинку иконки
        onAttachmentIcon (e) {
            this.mark.icon= e.target.files[0]
        },

        //цепляем картинку баннера
        onAttachmentBanner (e) {
            this.mark.banner = e.target.files[0]
        },

        //цепляем картинку цвета и записываем в выбранный цвет
        onAttachmentColor(e) {
            var colorId = e.target.getAttribute('id')
            this.mark.colors.forEach(function(item,i){
                if(colorId == item.id)
                    item.img = e.target.files[0]
            })
        },



        loadData(id) {
            edit(this, '/api/marks/' + this.urlId + '/edit', 'mark', 'message')
        },

        updateData(id) {
            update(this, '/api/marks/' + this.urlId, this.getFormData('patch'), 'mark', 'message')
        },

        storeData() {
            storage(this, '/api/marks/', this.getFormData(), 'mark', 'message', 'urlId', 'marks')
        },

        getFormData(method = '') {
            var formData = new FormData();

            formData.append('name',                 this.mark.name);
            formData.append('prefix',               this.mark.prefix ?? '');
            formData.append('status',               Number(this.mark.status));
            formData.append('show_driver',          Number(this.mark.show_driver));
            formData.append('show_toxic',          Number(this.mark.show_toxic));
            formData.append('brand_id',             this.mark.brand_id);
            formData.append('country_factory_id',   this.mark.country_factory_id);
            formData.append('body_work_id',         this.mark.body_work_id);
            formData.append('info[slogan]',          this.mark.info.slogan);
            formData.append('info[description]',     this.mark.info.description);

            if(isObject(this.mark.document.brochure))
                formData.append('document[brochure]',   this.mark.document.brochure);
            if(isObject(this.mark.document.manual))
                formData.append('document[manual]',     this.mark.document.manual);
            if(isObject(this.mark.document.price))
                formData.append('document[price]',      this.mark.document.price);
            if(isObject(this.mark.document.accessory))
                formData.append('document[accessory]',  this.mark.document.accessory);

            formData.append('icon', this.mark.icon);
            formData.append('banner', this.mark.banner);

            this.mark.properties.forEach(function(item, i){
                formData.append('properties['+item.id+']',     item.value);
            })

            this.mark.colors.forEach(function(item, i){
                formData.append('colors[' + item.id + ']', item.img);
            })

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

<style scoped>
.mark-colors img{
    width: 100%;
}
</style>
