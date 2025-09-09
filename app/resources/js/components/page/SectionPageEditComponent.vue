<template>
    <div class="color-edit">

        <spin v-if="loading && urlId"></spin>

        <error v-if="notFound"></error>

        <div v-else>
            <form>
                <div class="h5">{{ sectionpage.name ? sectionpage.name : 'Новый раздел' }}</div>

                <div class="row">
                    <div class="col text-right">
                        <label class="checkbox " :title="'Статус'">
                            <input class="device-checkbox-toggle" type="checkbox" v-bind:value="sectionpage.status" v-model="sectionpage.status">
                            <div class="checkbox__text" style="">
                                <div style="width: 200px;text-align: left;">
                                    {{ (sectionpage.status) ? 'Раздел включен' : 'раздел выключен' }}
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="row pb-3">
                    <div class="col-6">
                        <TextInput :label="'Название'" v-model="sectionpage.name" ></TextInput>
                        <BrandSelect v-model="sectionpage.brand_id"></BrandSelect>
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
            sectionpage: {
                name: '',
                sort: 1,
                status: 0,
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
        loadData(id) {

            this.loading = true;

            axios.get('/api/sectionpages/' + id + '/edit')
            .then( response => {
                this.sectionpage = response.data.data

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

            axios.patch('/api/sectionpages/' + id, this.sectionpage, this.getConfig())
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
            axios.post('/api/sectionpages/', this.sectionpage, this.getConfig())
            .then(res => {
                if(res.data.status == 1) {
                    this.succes = true;
                    this.succesMessage = res.data.message;
                    this.$router.push('/sectionpages/list')
                    this.$router.push('/sectionpages/edit/'+res.data.data.id)
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

        getConfig() {
            return {
                'content-type': 'application/json'
            }
        },
    },
    watch: {

    }
}
</script>
