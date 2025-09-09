<template>
    <div class="color-edit">

        <spin v-if="loading && urlId"></spin>

        <error v-if="notFound"></error>

        <div v-else>
            <form>
                <div class="h5">{{ pack.code ? pack.code : 'Новая опция' }}</div>

                <div class="">
                    <span class="badge badge-secondary" v-for="(device,i) in installDevices" :key="'b1'+i" style="margin-right:3px;">
                        {{device}}
                    </span>
                </div>

                <div class="row">
                    <div class="col-12 text-right">
                        <label class="checkbox align-items-center" title="Цветовая опция">
                            <input class="device-checkbox-toggle" type="checkbox" v-bind:value="pack.colored" v-model="pack.colored">
                            <div class="checkbox__text" style="">
                                Цветовая опция
                            </div>
                        </label>
                    </div>
                </div>

                <div class="row pb-3">
                    <div class="col-6">
                        <div >
                            <label for="name">Название</label>
                            <input type="text" name="name" v-model="pack.name" class="form-control"/>
                        </div>

                        <div >
                            <label for="name">Код</label>
                            <input type="text" name="code" v-model="pack.code" class="form-control"/>
                        </div>
                    </div>

                    <div class="col-6">
                        <div >
                            <label for="name">Цена</label>
                            <input type="text" name="price" v-model="pack.price" class="form-control"/>
                        </div>

                        <BrandSelect
                            name="'brand_id'"
                            v-model="pack.brand_id"
                        >
                        </BrandSelect>
                    </div>
                </div>

                <div class="pb-3">
                    <MarkGroupCheckBox
                        :install="pack.marks"
                        :brand="pack.brand_id"
                        @checkMark="setMarks"
                    >
                    </MarkGroupCheckBox>
                </div>

                <div class=" pb-3">
                    <DeviceGroupCheckbox
                        :install="pack.devices"
                        @checkDevice="setDivices"
                        :brand="pack.brand_id"
                    >
                    </DeviceGroupCheckbox>
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
import BrandSelect from '../html/BrandSelect';
import DeviceGroupCheckbox from '../checkbox/DeviceGroupCheckBox';
import MarkGroupCheckBox from '../checkbox/MarkGroupCheckBox';

export default {
    name: 'pack-edit',
    components: {
        Error, Message, Spin, BrandSelect,DeviceGroupCheckbox, MarkGroupCheckBox
    },

    data() {
        return {
            pack: {
                name: '',
                code: '',
                price: '',
                colored: false,
                brand_id: 0,
                devices: [],
                marks: []
            },
            notFound: false,
            loading: true,
            urlId: this.$route.params.id,
            succes: false,
            message: null,
            installDevices: [],
            previusPage: '/'
        }
    },
    mounted() {
        this.previusPage = this.prevRoute.fullPath
        if(this.urlId)
            this.loadData(this.urlId)
    },
    computed: {

    },
    methods: {
        setDivices(data) {
            this.pack.devices = data.devices
        },

        setMarks(data) {
            this.pack.marks = data.marks
        },

        loadData(id) {
            edit(this, '/api/packs/' + this.urlId + '/edit', 'pack', 'message')
        },

        updateData(id) {
            update(this, '/api/packs/' + this.urlId, this.getFormData('patch'), 'pack', 'message')
        },

        storeData() {
            storage(this, '/api/packs/', this.getFormData(), 'pack', 'message', 'urlId', 'packs')
        },

        getFormData(method = '') {
            var formData = new FormData();

            formData.append('name', this.pack.name);
            formData.append('code', this.pack.code);
            formData.append('price', this.pack.price);
            formData.append('colored', Number(this.pack.colored));
            formData.append('brand_id', this.pack.brand_id);

            this.pack.devices.forEach(itemDevice => {
                formData.append('devices[]', itemDevice)
            })

            this.pack.marks.forEach(itemMark => {
                formData.append('marks[]', itemMark)
            })

            if(method == 'patch')
                formData.append("_method", "PATCH");

            return formData;
        },
    },
    watch: {
        'pack.devices': {
            immediate: true,
            handler() {
                axios.get('/api/services/html/select/devices?ids='+this.pack.devices.join(','))
                .then((res) => {
                    this.installDevices = res.data.data
                })
                .catch(errors => {

                })
            },
        }
    },
    beforeRouteEnter(to, from, next) {
        next(vm => {
            vm.prevRoute = from;
        });
    },
}
</script>
