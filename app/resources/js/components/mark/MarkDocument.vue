<template>
    <div class="row py-3">
        <div class="col-12 h5 mb-3">
            Документы модели
        </div>

        <div class="col">

            <div class="row">
                <div class="col">
                    Брошюра
                </div>
                <div class="col text-right" v-if="brochure">
                       <span class="pointer" aria-hidden="true" @click="delDocument('brochure')">&times;</span>
                </div>
            </div>
            <div v-if="(brochure)" class="pb-3">
                <a :href="mark.document.brochure" target="_blank">Смотреть</a>
            </div>
            <div v-else class="pb-3 text-muted">
                {{ mark.document.brochure ? 'Новый фаил выбран' : 'Фаил отсутствует'}}
            </div>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="brochure" name="brochure" @change="onAttachmentChange">
                <label class="custom-file-label" for="brochure">Выберите фаил</label>
                <div class="invalid-feedback">Example invalid custom file feedback</div>
            </div>
        </div>

        <div class="col">
            <div class="row">
                <div class="col">
                    Прайс
                </div>
                <div v-if="price" class="col text-right">
                    <span class="pointer" aria-hidden="true" @click="delDocument('price')">&times;</span>
                </div>
            </div>
            <div v-if="price" class="pb-3">
                <a :href="mark.document.price" target="_blank">Смотреть</a>
            </div>
            <div v-else class="pb-3 text-muted">
                {{ mark.document.price ? 'Новый фаил выбран' : 'Фаил отсутствует'}}
            </div>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="price" name="price"  @change="onAttachmentChange">
                <label class="custom-file-label" for="price">Выберите фаил</label>
                <div class="invalid-feedback">Example invalid custom file feedback</div>
            </div>
        </div>

        <div class="col">
            <div class="row">
                <div class="col">
                    Мануал
                </div>
                <div class="col text-right" v-if="manual">
                       <span class="pointer" aria-hidden="true" @click="delDocument('manual')">&times;</span>
                </div>
            </div>
            <div v-if="manual" class="pb-3">
                <a :href="mark.document.manual" target="_blank">Смотреть</a>
            </div>
            <div v-else class="pb-3 text-muted">
                {{ mark.document.manual ? 'Новый фаил выбран' : 'Фаил отсутствует'}}
            </div>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="manual" name="manual"  @change="onAttachmentChange">
                <label class="custom-file-label" for="manual">Выберите фаил</label>
                <div class="invalid-feedback">Example invalid custom file feedback</div>
            </div>
        </div>

        <div class="col">
            <div class="row">
                <div class="col">
                    Аксессуары
                </div>
                <div class="col text-right" v-if="accessory">
                       <span class="pointer" aria-hidden="true" @click="delDocument('accessory')">&times;</span>
                </div>
            </div>
            <div v-if="accessory" class="pb-3">
                <a :href="mark.document.accessory" target="_blank">Смотреть</a>
            </div>
            <div v-else class="pb-3 text-muted">
                {{ mark.document.accessory ? 'Новый фаил выбран' : 'Фаил отсутствует'}}
            </div>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="accessory" name="accessory"  @change="onAttachmentChange">
                <label class="custom-file-label" for="accessory">Выберите фаил</label>
                <div class="invalid-feedback">Example invalid custom file feedback</div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'MarkDocument',
    data() {
        return {
            type: '',
        }
    },
    props: {
        mark: {
            document: {
                brochure: '',
                price: '',
                accessory: '',
                manual: '',
            }
        },
    },
    computed: {
        price() {
            return isString(this.mark.document.price) && this.mark.document.price!=''
        },
        manual() {
            return isString(this.mark.document.manual) && this.mark.document.manual!=''
        },
        brochure() {
            return isString(this.mark.document.brochure) && this.mark.document.brochure!=''
        },
        accessory() {
            return isString(this.mark.document.accessory) && this.mark.document.accessory!=''
        },
    },
    methods: {
        delDocument(type) {
            this.type = type
            this.mark.document[type] = ''
            axios.post('/api/services/marks/document', this.getFormData(), this.getConfig())
            .then(res => {

            }).catch(error => {

            }).finally(()=>{

            })
        },

        getFormData(method = '') {
            var formData = new FormData();
            formData.append('mark_id', this.mark.id)
            formData.append('type', this.type)
            formData.append("_method", "delete");
            return formData
        },

        getConfig() {
            return {
                'content-type': 'multipart/form-data'
            }
        },

        onAttachmentChange (e) {
            var fileProperty = e.target.getAttribute('id')
            this.mark.document[fileProperty] = e.target.files[0]
        },
    }
}
</script>

<style>
.pointer{
    cursor: pointer;
}
</style>
