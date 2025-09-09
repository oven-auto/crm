<template>
    <div class="brand-edit">

        <spin v-if="loading && urlId"></spin>

        <div v-else>
            <form>
                <div class="h5">{{ data.name ? data.name : 'Новый бренд' }}</div>

                <div class="row pb-3">
                    <div class="col-6">
                        <div >
                            <label for="name">Название</label>
                            <input type="text" name="name" v-model="data.name" class="form-control"/>
                        </div>

                        <div class="pt-3">


                            <div class="pb-3">
                                <label for="name">Цвет бренда</label>
                                <input type="color" v-model="data.brand_color" class="form-control" required>
                            </div>

                            <div class="">
                                <label for="name">Цвет текста</label>
                                <input type="color" v-model="data.font_color" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <label for="icon">Иконка</label>

                        <div v-if="data.icon" class="pb-3">
                            <img :src="data.icon" class="brand-icon">
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

export default {
    name: 'brand-edit',
    components: {
        Error,
        Spin,
        Message
    },
    data() {
        return {
            data: {
                name: null,
                icon: null,
                brand_color: null,
                font_color: null
            },
            loading: true,
            urlId: this.$route.params.id,
            message: null,
        }
    },

    mounted() {
        if(this.urlId)
            this.loadData()
    },

    methods: {
        onAttachmentChange (e) {
            this.data.icon = e.target.files[0];
        },

        loadData(id) {
            edit(this, '/api/brands/' + this.urlId + '/edit', 'data', 'message')
        },

        updateData(id) {
            update(this, '/api/brands/' + this.urlId, this.getFormData('patch'), 'data', 'message')
        },

        storeData() {
            storage(this, '/api/brands/', this.getFormData(), 'data', 'message', 'urlId', 'brands')
        },

        getFormData(method = '') {
            var formData = new FormData();

            formData.append('name', this.data.name);
            formData.append('icon', this.data.icon);
            formData.append('brand_color', this.data.brand_color);
            formData.append('font_color', this.data.font_color);

            if(method == 'patch')
                formData.append("_method", "PATCH");

            return formData;
        },
    }
}
</script>

<style scoped>
    .brand-icon{
        height: 120px;
    }
</style>
