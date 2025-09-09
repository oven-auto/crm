<template>
    <div class="color-edit">

        <spin v-if="loading && urlId"></spin>

        <error v-if="notFound"></error>

        <div v-else>
            <form>
                <div class="h5">{{ shortcut.name ? shortcut.name : 'Новый ярлык' }}</div>

                <div class="row">
                    <div class="col text-right">
                        <label class="checkbox " :title="'Статус'">
                            <input class="device-checkbox-toggle" type="checkbox" v-bind:value="shortcut.status" v-model="shortcut.status">
                            <div class="checkbox__text" style="">
                                <div style="width: 200px;text-align: left;">
                                    {{ (shortcut.status) ? 'Ярлык включен' : 'Ярлык выключен' }}
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="row pb-3">
                    <div class="col-6">
                        <TextInput :label="'Тех.название'" v-model="shortcut.name" ></TextInput>
                        <BrandSelect v-model="shortcut.brand_id"></BrandSelect>
                        <TextInput :label="'Заголовок'" v-model="shortcut.title" ></TextInput>
                        <TextInput :label="'Ссылка'" v-model="shortcut.link" ></TextInput>
                        <TextInput :label="'Иконка'" v-model="shortcut.icon" ></TextInput>
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
            shortcut: {
                name: '',
                title: '',
                link: '',
                icon: '',
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
        loadData(id) {

            this.loading = true;

            axios.get('/api/shortcuts/' + id + '/edit')
            .then( response => {
                this.shortcut = response.data.data

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

            axios.patch('/api/shortcuts/' + id, this.shortcut, this.getConfig())
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
            axios.post('/api/shortcuts/', this.shortcut, this.getConfig())
            .then(res => {
                if(res.data.status == 1) {
                    this.succes = true;
                    this.succesMessage = res.data.message;
                    this.$router.push('/shortcuts/list')
                    this.$router.push('/shortcuts/edit/'+res.data.data.id)
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
