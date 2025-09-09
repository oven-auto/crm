<template>
    <div id="credit-edit">

        <spin v-if="loading && urlId"></spin>

        <error v-if="notFound"></error>

        <div v-else>
            <form>
                <div class="h5">{{ credit.name ? credit.name : 'Новый кредит' }}</div>

                <div class="row">
                    <div class="col text-right">
                        <label class="checkbox " :title="'Статус'">
                            <input class="device-checkbox-toggle" type="checkbox" v-bind:value="credit.status" v-model="credit.status">
                            <div class="checkbox__text" style="">
                                <div style="width: 200px;text-align: left;">
                                    {{ (credit.status) ? 'Кредит включен' : 'Кредит выключен' }}
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="row pb-3">
                    <div class="col-6">
                        <BrandSelect v-model="credit.brand_id"></BrandSelect>

                        <TextInput :label="'Название'" v-model="credit.name" ></TextInput>

                        <RangeInput :label="'Ставка (%)'" v-model="credit.rate" :min="0" :max="20" :step="0.1"></RangeInput>

                        <RangeInput :label="'Срок кредита (лет)'" v-model="credit.period" :min="1" :max="7"></RangeInput>

                        <RangeInput :label="'ПВ (%)'" v-model="credit.contribution" :min="0" :max="100"></RangeInput>

                        <RangeInput :label="'Платеж (руб.)'" v-model="credit.pay" :min="0" :max="100000"></RangeInput>

                        <div class="row">
                            <div class="col">
                                <DateInput :label="'Начало'" v-model="credit.begin_at"></DateInput>
                            </div>
                            <div class="col">
                                <DateInput :label="'Конец'" v-model="credit.end_at"></DateInput>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div>
                             <label for="icon">Банер</label>

                            <div v-if="credit.banner" class="pb-3">
                                <img :src="credit.banner" class="credit-icon">
                            </div>

                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="icon" name="icon" @change="onAttachmentChange">
                                <label class="custom-file-label" for="icon">Выберите фаил</label>
                                <div class="invalid-feedback">Example invalid custom file feedback</div>
                            </div>
                        </div>

                        <CreditMark :brand="credit.brand_id" v-model="credit.marks" @onChange="getMarks"></CreditMark>
                    </div>
                </div>

                <div class="row pb-3">
                    <div class="col">
                        <TextArea :label="'Дисклеймер'" v-model="credit.disclaimer"></TextArea>
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

import CreditMark from './CreditMarkComponent';

export default {
    name: 'credit-edit',
    components: {
        Error, Message, Spin, BrandSelect, TextInput,TextArea, RangeInput, DateInput, CreditMark
    },
    data() {
        return {
            credit: {
                brand_id: 0,
                status: 0,
                name: '',
                banner: '',
                rate: 0,
                pay: 0,
                period: 0,
                contribution: 0,
                begin_at: '',
                end_at: '',
                disclaimer: '',
                marks: []
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
            this.credit.banner = e.target.files[0]
        },
        getMarks(param){
            this.credit.marks = param
        },

        loadData(id) {

            this.loading = true;

            axios.get('/api/credits/' + id + '/edit')
            .then( response => {
                this.credit = response.data.data
                this.loading = false;

            })
            .catch(errors => {
                this.notFound = true;
                this.loading = false;
            })
            .finally( () => {
                this.loading = false;
            })
        },

        updateData(id) {
            this.loading = true;

            axios.post('/api/credits/' + id, this.getFormData('patch'), this.getConfig())
            .then(res => {
                if(res.data.status == 1) {
                    this.succes = true;
                    this.succesMessage = res.data.message;
                    this.loadData(id)
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
                makeToast(this,this.succesMessage)
            })
        },

        storeData() {
            this.loading = true;
            axios.post('/api/credits/', this.getFormData(), this.getConfig())
            .then(res => {
                if(res.data.status == 1) {
                    this.succes = true;
                    this.succesMessage = res.data.message;
                    this.$router.push('/credits/list')
                    this.$router.push('/credits/edit/'+res.data.data.id)
                    this.urlId = res.data.data.id
                    this.loadData(res.data.data.id)
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
                makeToast(this,this.succesMessage)
            })
        },

        getFormData(method = '') {
            var formData = new FormData();

            formData.append('brand_id', this.credit.brand_id);
            formData.append('name', this.credit.name);
            formData.append('status', Number(this.credit.status));
            formData.append('rate', this.credit.rate);
            formData.append('pay', this.credit.pay);
            formData.append('period', this.credit.period);
            formData.append('contribution', this.credit.contribution);
            formData.append('begin_at', this.credit.begin_at);
            formData.append('end_at', this.credit.end_at);
            formData.append('banner', this.credit.banner);
            formData.append('disclaimer', this.credit.disclaimer);
            this.credit.marks.forEach( (item) => {
                formData.append('marks[]', item);
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
    },
    watch: {

    }
}
</script>

<style scoped>
#credit-edit .credit-icon{
    width: 100%;
}
</style>
