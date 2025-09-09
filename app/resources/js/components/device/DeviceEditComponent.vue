<template>
<div class="device-edit">


    <spin v-if="loading && urlId"></spin>

    <div v-else>
        <form>
            <div class="h5">{{ device.name ? device.name : 'Новое оборудование' }}</div>

            <div class="row pb-3">
                <div class="col-6">
                    <div >
                        <label for="name">Название</label>
                        <input
                            type="text"
                            name="name"
                            v-model="device.name"
                            class="form-control"
                        />
                    </div>

                    <div class="pt-3">
                        <label for="name">Бренд</label>
                        <HtmlMultiSelect
                            :name="'brand_id'"
                            :data="brands"
                            v-model="device.brands"
                            :placeholder="'Не выбрано'">
                        </HtmlMultiSelect>
                    </div>

                    <div class="pt-3">
                        <div class="row">
                            <div class="col">
                                <DeviceTypeSelect v-model="device.device_type_id" :label="'Категория оборудования'"></DeviceTypeSelect>
                            </div>

                            <div class="col">
                                <vRange v-model="device.install_target" :min="0" :max="1" :step="0.01" :label="'ЦКУ'" :title="'Целевой коэффициент установки'"></vRange>
                            </div>
                        </div>
                    </div>

                    <div class="pt-3">
                        <DeviceFilterSelect v-model="device.device_filter_id"></DeviceFilterSelect>
                    </div>

                    <CheckBox class="pt-3" v-model="device.tuning" :label="'Использовать в тюнинге'"></CheckBox>

                </div>

                <div class="col-6">
                    <label for="icon">Изображение</label>

                    <div v-if="device.image" class="pb-3">
                        <img :src="device.image" class="brand-icon" style="width:100%;">
                    </div>

                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="icon" name="icon" @change="onAttachmentChange">
                        <label class="custom-file-label" for="icon">Выберите фаил</label>
                        <div class="invalid-feedback">Example invalid custom file feedback</div>
                    </div>
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
import HtmlSelect from '../html/HtmlSelect';
import HtmlMultiSelect from '../html/HtmlMultiSelect';
import DeviceFilterSelect from '../html/Select/FilterDeviceSelect';
import DeviceTypeSelect from '../html/Select/DeviceTypeSelect';
import CheckBox from '../checkbox/CheckBox';
import vText from '../html/TextInput'
import vRange from '../html/RangeInput'

export default {
    name: 'device-filter-edit',
    components: {
        Error, Message, Spin, HtmlSelect, HtmlMultiSelect,DeviceFilterSelect,DeviceTypeSelect,CheckBox,vText,vRange
    },
    data() {
        return {
            device: {
                name: '',
                device_type_id: '',
                device_filter_id: '',
                brands: [],
                tuning: '',
                install_target: 0,
                image: ''
            },

            brands: [],
            loading: true,
            urlId: this.$route.params.id,
            message: null,
            previusPage: '/'
        }
    },


    mounted() {
        this.previusPage = this.prevRoute.fullPath
        this.loadBrands();

        if(this.urlId)
            this.loadDevice();
    },

    methods: {

        onAttachmentChange (e) {
            this.device.image = e.target.files[0];
        },

        loadDevice(id) {
            edit(this, '/api/devices/' + this.urlId + '/edit', 'device', 'message')
        },

        updateData(id) {
            update(this, '/api/devices/' + this.urlId, this.getFormData('patch'), 'device', 'message')
        },

        storeData() {
            storage(this, '/api/devices/', this.getFormData(), 'device', 'message', 'urlId', 'devices')
        },

        getFormData(method = '') {
            var formData = new FormData();
            formData.append('name',                 this.device.name);
            formData.append('device_type_id',       this.device.device_type_id);
            formData.append('device_filter_id',     this.device.device_filter_id ?? '');
            formData.append('tuning',               Number(this.device.tuning))
            formData.append('install_target',       this.device.install_target);
            formData.append('image',                this.device.image)

            Object.values(this.device.brands).forEach(item => {
                formData.append('brand_id[]', item)
            })

            if(method == 'patch')
                formData.append("_method", "PATCH");
            return formData;
        },

        loadBrands() {
            axios.get('/api/brands')
            .then(response => {
                if(response.data.status == 1)
                    this.brands = response.data.data;
            })
            .catch(errors => {
                this.loading = false;
            })
        },
    },
    beforeRouteEnter(to, from, next) {
        next(vm => {
            vm.prevRoute = from;
        });
    },
}
</script>
