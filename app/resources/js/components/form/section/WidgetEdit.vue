<template>
    <section>
        <div class="row pt-3">
            <div class="col">
                <div >
                    <img :src="value.banner.image" v-if="value.banner.image" style="height: 100px;">
                </div>
            </div>
            <div class="col">
                <TextBox v-model="value.description" :label="'Описание'"></TextBox>
            </div>
        </div>

        <div class="row">
            <div class="col">

                <label for="icon">Банер</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" @change="onAttachmentChange">
                    <label class="custom-file-label" for="icon">Выберите фаил</label>
                    <div class="invalid-feedback">Example invalid custom file feedback</div>
                </div>
            </div>
            <div class="col">
                <label for="icon">Расположение банера</label>
                <select class="form-control" v-model="value.banner.position">
                    <option selected disabled>Укажите параметр</option>
                    <option value="left">Слева от описания</option>
                    <option value="right">Справа от описания</option>
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <div class="border ">
                    <div class="badge-control-header border-bottom p-1 px-3">
                        <span>
                            <button class="fa fa-align-left" aria-hidden="true" @click="badgeAlign('left')"></button>
                        </span>
                        <span>
                            <button class="fa fa-align-center" aria-hidden="true" @click="badgeAlign('center')"></button>
                        </span>
                        <span>
                            <button class="fa fa-align-right" aria-hidden="true" @click="badgeAlign('right')"></button>
                        </span>
                        <span>
                            <button class="fa fa-align-justify" aria-hidden="true" @click="badgeAlign('justify')"></button>
                        </span>



                        <span style="display:inline-block;">
                            <CheckBox :label="'Связать линиями'" v-model="value.badge_line"></CheckBox>
                        </span>
                        <span style="display:inline-block;">
                            <CheckBox :label="'Объеденить в таблицу'" v-model="value.badge_table"></CheckBox>
                        </span>
                        <span style="display:inline-block;">
                            <CheckBox :label="'Пронумеровать'" v-model="value.badge_number"></CheckBox>
                        </span>



                        <button class="btn btn-dark" @click="addBadge()">
                            Добавить бейдж
                        </button>
                    </div>

                    <div class="row py-3 px-3">
                        <div class="col-2" v-for="(item,i) in value.badges" :key="'badge-block'+i" >
                            <div class="border badge-control">
                                <div>
                                    <img v-if="item.image" :src="item.urlimage" style="width:100%;">
                                    <i v-if="item.icon" :style="{'font-size':'50px'}" :class="item.icon"></i>
                                </div>
                                <div class="custom-file">
                                    <input
                                        type="file"
                                        class="custom-file-input"
                                        :index="i"
                                        name="icon"
                                        @change="onAttachmentBadgeFile"
                                        style="height:auto;"
                                    >
                                    <label class="custom-file-label" for="icon" style="border-radius:0px;border-left:0px;border-top:0px;border-right:0px;">Выберите фаил</label>
                                    <div class="invalid-feedback">Example invalid custom file feedback</div>
                                </div>

                                <div class="">
                                    <input type="text" class="" style="width:100%;border-bottom:1px solid #ddd;" v-model="item.icon" placeholder="Иконка">
                                </div>

                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <input type="text" class="" style="border-right:1px solid #ddd;" v-model="item.size" placeholder="Размер">
                                    <input type="text" class="" v-model="item.color" placeholder="Цвет">
                                </div>

                                <div class="">
                                    <textarea placeholder="Описание" rows="5" class="border-top" v-model="item.description">
                                    </textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-6 ">
                            <div class="p-3">
                                <label>Расположение иконок</label>
                                <select class="form-control" v-model="value.badge_position">
                                    <option selected disabled>Укажите параметр</option>
                                    <option value="1">По умолчанию</option>
                                    <option value="2">Вместо банера</option>
                                    <option value="3">Под описанием</option>
                                    <option value="4">Над описанием</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <VueEditor
                    v-model="value.body.text"
                    :editorOptions="editorSettings"
                ></VueEditor>
            </div>
        </div>


    </section>
</template>

<script>
import TextBox from '../../html/TextArea';
import CheckBox from '../../checkbox/CheckBox';

import { VueEditor, Quill } from 'vue2-editor'
// //import { ImageDrop } from 'quill-image-drop-module';
// import BlotFormatter from 'quill-blot-formatter';
// //Quill.register("modules/imageDrop", ImageDrop);
// Quill.register('modules/blotFormatter', BlotFormatter);

export default {
    name: 'widget-edit',
    components: {TextBox, VueEditor,CheckBox},
    data() {
        return {
            editorSettings: {
                modules: {
                    blotFormatter: {}
                }
            },
        }
    },
    props: ['form', 'value'],
    methods: {
        onAttachmentChange(e) {
            this.value.banner.image = e.target.files[0]
        },
        onAttachmentBadgeFile(e) {
            var i = e.target.getAttribute('index')
            this.value.badges[i].image = e.target.files[0]
        },
        addBadge() {
            this.value.badges.push({
                image: '', icon: '', size: '', color: '', description: ''
            })
        },
        badgeAlign(param) {
            this.value.badge_align = param
        }
    }
}
</script>

<style scoped>
.badge-control input, .badge-control textarea{
    border-radius: 0px;
    border: 0px;
    width: 100%;
    display: block;
    padding: 0 15px;
}
</style>
