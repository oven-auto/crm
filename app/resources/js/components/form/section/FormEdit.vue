<template>
<div>

    <spin v-if="loading && urlId"></spin>

    <error v-if="notFound"></error>

    <div v-else>
        <div class="row">
            <div class="col">
                <div class="h-title">Новая форма</div>
            </div>

                <div class="col text-right">
                    <label class="checkbox " :title="'Статус'">
                        <input class="device-checkbox-toggle" type="checkbox" v-bind:value="form.menu_status" v-model="form.menu_status">
                        <div class="checkbox__text" style="">
                            <div style="width: 200px;text-align: left;">
                                {{ (form.menu_status) ? 'Видна в меню' : 'Не видна в меню' }}
                            </div>
                        </div>
                    </label>
                </div>
        </div>

        <div class="row">
            <div class="col-6">
                <InputBox v-model="form.name" :label="'Название'"></InputBox>

                <InputBox v-model="form.title" :label="'Заголовок'"></InputBox>
            </div>

            <div class="col-6">
                <TextBox v-model="form.description" :label="'Описание'"></TextBox>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <FormEventSelect v-model="form.form_event_id" :label="'Связь с кнопкой'"></FormEventSelect>
            </div>

            <div class="col">
                <PageSection v-model="form.section_page_id" :label="'Раздел сайта'"></PageSection>
            </div>
        </div>

        <!-- <div class="row py-2">
            <div class="col border py-2">
                <div class="row">
                    <div v-if="form.banner" class="pb-3 col-4">
                        <img :src="form.banner" class="brand-icon" style="width:100%;">
                    </div>

                    <div class="col">
                        <label for="icon">Банер</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="icon" name="icon" @change="onAttachmentChange">
                            <label class="custom-file-label" for="icon">Выберите фаил</label>
                            <div class="invalid-feedback">Example invalid custom file feedback</div>
                        </div>
                        <div class="">
                            <label>Цвет в виджете(необязательное поле)</label>
                            <input type="color" v-model="form.color" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="row pt-3">
            <div class="col-6">
                <FormControllCheckBox :value="form.bodies" @checkControlls="setControlls"></FormControllCheckBox>
            </div>
            <div class="col-6">
                <UserCheckBox :value="form.recipients" @checkUsers="setUsers"></UserCheckBox>
            </div>
        </div>

        <div class="row border  mb-3" style="background: #efefef;" >
            <div class="col" style="background: #666;color:#ddd">
                <label class="checkbox " :title="'Статус'" style="margin: 0px;">
                    <input class="device-checkbox-toggle" type="checkbox" v-bind:value="form.widget_status" v-model="form.widget_status">
                    <div class="checkbox__text" style="">
                        <div style="width: 200px;text-align: left;">
                            {{ (form.widget_status) ? 'Виджет включен' : 'Виджет выключен' }}
                        </div>
                    </div>
                </label>
            </div>

            <div class="col-12 pb-3" v-if="form.widget_status">
                <WidgetEdit  :form="form" v-model="form.widget"></WidgetEdit>
            </div>
        </div>



        <FormControll :id="urlId"></FormControll>

    </div>
</div>
</template>

<script>
import Error from '../../alert/ErrorComponent';
import Message from '../../alert/MessageComponent';
import Spin from '../../spinner/SpinComponent';

import CheckBox from '../../checkbox/CheckBox';
import UserCheckBox from '../../checkbox/UsersCheckBox';
import FormControllCheckBox from '../../checkbox/FormControllCheckbox';
import InputBox from '../../html/TextInput';
import TextBox from '../../html/TextArea';
import FormEventSelect from '../../html/Select/FormEventSelect';
import PageSection from '../../html/SectionPageSelect';



import WidgetEdit from './WidgetEdit'

export default {
    name: 'form-edit',
    components: {WidgetEdit, CheckBox, UserCheckBox, InputBox,TextBox, FormEventSelect, Error, Message, Spin,FormControllCheckBox, PageSection},
    data() {
        return {

            loading: false,
            form: {
                id: '',
                bodies: [],
                form_section_id: this.$route.query.section_id,
                form_event_id: 0,
                name: '',
                description: '',
                recipients: [],
                menu_status: '',
                section_page_id: 0,
                title: '',
                widget_status: 0,
                widget: {
                    banner: {
                        image: '',
                        position: ''
                    },
                    body: {
                        text: ''
                    },
                    badges: [],
                    badge_align: 'left',
                    badge_line: 0,
                    badge_table: 0,
                    badge_number: 0,
                    badge_position: 1,
                    description: ''
                }
            },

            controlls: {},
            urlId: this.$route.params.id,
            succes: false,
            succesMessage: null,
            notFound: false,
        }
    },
    mounted() {
        if(this.urlId)
            this.loadData()
    },

    methods: {
        onAttachmentChange(e) {
            this.form.banner = e.target.files[0]
        },

        setUsers(data) {
            this.form.recipients = data.users
        },

        setControlls(data) {
            this.form.bodies = data.controlls
        },


        storeData() {
            this.loading = true
            var url = '/api/forms/formcreate';
            axios.post(url, this.getFormData(), this.getConfig())
            .then(res => {
                this.$router.push('/forms/list')
                this.$router.push('/forms/formedit/'+res.data.data.id)
                this.urlId = res.data.data.id
                this.succes = true
                this.succesMessage = 'Успешно создано'
                this.loadData()
            }).catch(errors => {

            }).finally(() => {
                this.loading = false
                makeToast(this,this.succesMessage)
            })
        },

        updateData() {
            this.loading = true
            var url = '/api/forms/formupdate/'+this.urlId;
            axios.post(url, this.getFormData('patch'), this.getConfig())
            .then(res => {
                this.succes = true
                this.succesMessage = 'Успешно изменено'
                this.loadData()
            }).catch(errors => {

            }).finally(() => {
                this.loading = false
                makeToast(this,this.succesMessage)
            })
        },

        loadData() {
            this.loading = true
            var url = '/api/forms/formedit/'+this.urlId
            axios.get(url)
            .then(res => {
                this.form = res.data.data
            }).catch(errors => {
                this.notFound = true;
            }).finally(()=>{
                this.loading = false
            })
        },

        getFormData(method = '') {
            var formData = new FormData();

            formData.append('bodies',           this.form.bodies);
            formData.append('title',            this.form.title);
            formData.append('menu_status',      Number(this.form.menu_status));
            formData.append('form_section_id',  this.form.form_section_id);
            formData.append('form_event_id',    this.form.form_event_id);
            formData.append('description',      this.form.description);
            formData.append('name',             this.form.name);
            formData.append('recipients',       this.form.recipients);
            formData.append('section_page_id',  this.form.section_page_id);
            formData.append('banner',           this.form.banner);
            formData.append('color',            this.form.color);
            formData.append('widget_status',    Number(this.form.widget_status));
            if(this.form.widget_status) {
                formData.append('widget[badge_align]',      this.form.widget.badge_align)
                formData.append('widget[badge_line]',       Number(this.form.widget.badge_line))
                formData.append('widget[badge_table]',      Number(this.form.widget.badge_table))
                formData.append('widget[badge_number]',     Number(this.form.widget.badge_number))
                formData.append('widget[badge_position]',   this.form.widget.badge_position)
                formData.append('widget[description]',      this.form.widget.description)

                formData.append('widget[banner][image]',    this.form.widget.banner.image)
                formData.append('widget[banner][position]', this.form.widget.banner.position)

                formData.append('widget[body][text]', this.form.widget.body.text)

                this.form.widget.badges.forEach((item,i) => {
                    formData.append('widget[badges]['+i+'][image]', item.image || '')
                    formData.append('widget[badges]['+i+'][icon]', item.icon || '')
                    formData.append('widget[badges]['+i+'][size]', item.size || '')
                    formData.append('widget[badges]['+i+'][color]', item.color || '')
                    formData.append('widget[badges]['+i+'][description]', item.description || '')
                })
            } else {

            }

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
}
</script>

