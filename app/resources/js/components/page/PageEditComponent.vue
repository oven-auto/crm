<template>
    <div class="page-edit">

        <spin v-if="loading && urlId"></spin>

        <error v-if="notFound"></error>

        <div v-else>
            <form>
                <div class="h5">{{ page.name ? page.name : 'Новая страница' }}</div>

                <div class="row">
                    <div class="col text-right">
                        <label class="checkbox " :title="'Статус'">
                            <input class="device-checkbox-toggle" type="checkbox" v-bind:value="page.status" v-model="page.status">
                            <div class="checkbox__text" style="">
                                <div style="width: 200px;text-align: left;">
                                    {{ (page.status) ? 'Страница включена' : 'Страница выключена' }}
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="row pb-3">
                    <div class="col-8">
                        <SectionPage v-model="page.section_page_id"></SectionPage>

                        <TextInput :label="'Заголовок'" v-model="page.title" ></TextInput>

                        <div class="" v-for="(tool,i) in page.tools" :key="'tool'+i">
                            <div v-if="tool.type == 'pagetext'">
                                <div class="textarea">
                                    <label>Текст</label>
                                    <VueEditor
                                        v-model="tool.value"
                                        :editorOptions="editorSettings"
                                    ></VueEditor>
                                </div>
                            </div>

                            <div v-else-if="tool.type=='form'">
                                <FormSelect :widget="1" :label="'Выберите форму виджета'" v-model="tool.value"></FormSelect>
                            </div>
                        </div>

                        <!-- <div class="textarea">
                            <label>Текст</label>
                            <VueEditor
                                v-model="page.text"
                                :editorOptions="editorSettings"
                            ></VueEditor>
                        </div> -->

                    </div>

                    <div class="col-4">
                        <label>Инструменты</label>
                        <div class="">
                            <button type="button" class="btn btn-dark btn-block" @click="addText()">Добавить текст</button>
                            <button type="button" class="btn btn-dark btn-block" @click="addVidget()">Добавить виджет формы</button>
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
import SectionPage from '../html/SectionPageSelect';

import { VueEditor, Quill } from 'vue2-editor'
//import { ImageDrop } from 'quill-image-drop-module';
import BlotFormatter from 'quill-blot-formatter';
//Quill.register("modules/imageDrop", ImageDrop);
Quill.register('modules/blotFormatter', BlotFormatter);

import FormSelect from '../html/Select/FormSelect';

export default {
    name: 'page-edit',
    components: {
        Error, Message, Spin, BrandSelect, TextInput,TextArea, RangeInput, DateInput, SectionPage, VueEditor, FormSelect
    },

    data() {
        return {
            page: {
                title: '',
                text: '',
                status: 0,
                section_page_id: 0,
                tools: []
            },
            notFound: false,
            loading: true,
            urlId: this.$route.params.id,
            succes: false,
            succesMessage: null,

            editorSettings: {
                modules: {
                    blotFormatter: {}
                }
            }
        }
    },

    computed: {

    },

    mounted() {
        if(this.urlId)
            this.loadData(this.urlId)
    },
    methods: {
        addText() {
            this.page.tools.push({
                type: 'pagetext',
                sort: this.page.tools.length+1,
                value: ''
            })
        },

        addVidget() {
            this.page.tools.push({
                type: 'form',
                sort: this.page.tools.length+1,
                value: 0
            })
        },

        loadData(id) {

            this.loading = true;

            axios.get('/api/pages/' + id + '/edit')
            .then( response => {
                this.page.title = response.data.data.title
                this.page.text = response.data.data.text
                this.page.status = response.data.data.status
                this.page.section_page_id = response.data.data.section_page_id
                this.page.tools = response.data.data.tools
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

            axios.patch('/api/pages/' + id, this.page, this.getConfig())
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
            axios.post('/api/pages/', this.page, this.getConfig())
            .then(res => {
                if(res.data.status == 1) {
                    this.succes = true;
                    this.succesMessage = res.data.message;
                    this.$router.push('/pages/list')
                    this.$router.push('/pages/edit/'+res.data.data.id)
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

<style scoped>
.textarea img{
    width: auto !important;
}
</style>
