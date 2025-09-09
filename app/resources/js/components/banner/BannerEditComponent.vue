<template>
    <div class="color-edit">

        <spin v-if="loading && urlId"></spin>

        <error v-if="notFound"></error>

        <div v-else>
            <form>
                <div class="h5">{{ banner.name ? banner.name : 'Новый банер' }}</div>

                <div class="row">
                    <div class="col text-right">
                        <label class="checkbox " :title="'Статус'">
                            <input class="device-checkbox-toggle" type="checkbox" v-bind:value="banner.status" v-model="banner.status">
                            <div class="checkbox__text" style="">
                                <div style="width: 200px;text-align: left;">
                                    {{ (banner.status) ? 'Банер включен' : 'Банер выключен' }}
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="row pb-3">
                    <div class="col-6">
                        <TextInput :label="'Тех.название'" v-model="banner.name" ></TextInput>
                        <BrandSelect v-model="banner.brand_id"></BrandSelect>
                        <TextInput :label="'Заголовок'" v-model="banner.title" ></TextInput>
                        <TextArea :label="'Текст'" v-model="banner.text"></TextArea>
                        <TextInput :label="'Ссылка'" v-model="banner.link" ></TextInput>
                    </div>

                    <div class="col-6">
                        <div>
                             <label for="icon">Банер</label>

                            <div v-if="banner.image" class="pb-3">
                                <img :src="banner.image" class="brand-icon" style="width:100%;">
                            </div>

                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="icon" name="icon" @change="onAttachmentChange">
                                <label class="custom-file-label" for="icon">Выберите фаил</label>
                                <div class="invalid-feedback">Example invalid custom file feedback</div>
                            </div>
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

import BrandSelect from '../html/BrandSelect';
import TextInput from '../html/TextInput';
import TextArea from '../html/TextArea';
import RangeInput from '../html/RangeInput';
import DateInput from '../html/DateInput';


export default {
    name: 'credit-edit',
    components: {
        Error, Message, Spin, BrandSelect, TextInput,TextArea, RangeInput, DateInput
    },
    data() {
        return {
            banner: {
                name: '',
                title: '',
                text: '',
                link: '',
                image: '',
                sort: 1,
                status: '',
                brand_id: 0
            },
            notFound: false,
            loading: true,
            urlId: this.$route.params.id,
            succes: false,
            succesMessage: null,
        }
    },

    computed: {

    },

    mounted() {
        if(this.urlId)
            this.loadData(this.urlId)
    },
    methods: {
        onAttachmentChange(e) {
            this.banner.image = e.target.files[0]
        },

        loadData(id) {

            this.loading = true;

            axios.get('/api/banners/' + id + '/edit')
            .then( response => {
                this.banner = response.data.data
                this.banner.text = this.banner.text == 'null' ? '' : this.banner.text
            })
            .catch(errors => {
                this.notFound = true;
            })
            .finally( () => {
                this.loading = false;
            })
        },

        updateData(id) {
            this.loading = true;

            axios.post('/api/banners/' + id, this.getFormData('patch'), this.getConfig())
            .then(res => {
                if(res.data.status == 1) {
                    this.succes = true;
                    this.succesMessage = res.data.message;
                    this.loadData(id)
                    makeToast(this,this.succesMessage)
                } else {
                    this.succes = true;
                    this.succesMessage = res.data.message + ': ' + res.data.errors;
                }
            })
            .catch(errors => {
                console.log(errors)
            })
            .finally( () => {
                this.loading = false;
            })
        },

        storeData() {
            this.loading = true;
            axios.post('/api/banners/', this.getFormData(), this.getConfig())
            .then(res => {
                if(res.data.status == 1) {
                    this.succes = true;
                    this.succesMessage = res.data.message;
                    this.$router.push('/banners/list')
                    this.$router.push('/banners/edit/'+res.data.data.id)
                    this.urlId = res.data.data.id
                    this.loadData(res.data.data.id)
                    makeToast(this,this.succesMessage)
                } else {
                    this.succes = true;
                    this.succesMessage = res.data.message + ': ' + res.data.errors;
                }

            })
            .catch(errors => {
                console.log(errors)
            })
            .finally( () => {
                this.loading = false;
            })
        },

        getFormData(method = '') {
            var formData = new FormData();

            formData.append('brand_id', this.banner.brand_id);
            formData.append('title', this.banner.title);
            formData.append('status', Number(this.banner.status));
            formData.append('link', this.banner.link);
            formData.append('image', this.banner.image);
            formData.append('text', this.banner.text);
            formData.append('name', this.banner.name);
            formData.append('sort', this.banner.sort);

            if(method == 'patch')
                formData.append("_method", "PATCH");

            return formData;
        },

        getConfig() {
            return {
                'content-type': 'multipart/form-data'
            }
        },
    },
    watch: {

    }
}
</script>
