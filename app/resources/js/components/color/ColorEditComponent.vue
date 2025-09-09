<template>
    <div class="color-edit">
        <spin v-if="loading && urlId"></spin>

        <error v-if="notFound"></error>

        <div v-else>
            <form>
                <div class="h5">{{ color.name ? color.name : 'Новый цвет' }}</div>

                <div class="row pb-3">
                    <div class="col-6">
                        <div >
                            <label for="name">Название</label>
                            <input type="text" name="name" v-model="color.name" class="form-control"/>
                        </div>

                        <div >
                            <label for="name">Код</label>
                            <input type="text" name="name" v-model="color.code" class="form-control"/>
                        </div>

                        <BrandSelect
                            name="'brand_id'"
                            v-model="color.brand_id"
                        >
                        </BrandSelect>
                    </div>

                    <div class="col-6">
                        <div class="main-color">
                            <label>Основной цвет</label>
                            <input type="color" v-model="color.web_main" class="form-control">
                        </div>

                        <div v-if="sub_color">
                            <div class="sub_color">
                                <label>Дополнительный цвет</label>
                                <input type="color" v-model="color.web_sub" class="form-control" >
                                <a class=" d-block text-right" @click="colorTrue(0)">Удалить дополнительный цвет</a>
                            </div>
                        </div>

                        <div v-else>
                            <label>&nbsp</label>
                            <button type="button" class="btn btn-secondary btn-block" @click="colorTrue(1)">Дополнительный цвет</button>
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

export default {
    name: 'device-driver-edit',
    components: {
        Error, Message, Spin, BrandSelect
    },
    data() {
        return {
            color: {
                name: '',
                code: '',
                web_main: '',
                web_sub: '',
                brand_id: 0
            },
            sub_color: false,
            notFound: false,
            loading: true,
            urlId: this.$route.params.id,
            succes: false,
            succesMessage: null,
            previusPage: '/'
        }
    },
    mounted() {
        this.previusPage = this.prevRoute.fullPath
        if(this.urlId)
            this.loadData(this.urlId)
    },
    methods: {
        colorTrue(status) {
            if(status == 1)
                this.sub_color = true;
            else
                this.sub_color = false
        },

        loadData(id) {
            edit(this, '/api/colors/' + this.urlId + '/edit', 'color', 'message')
        },

        updateData(id) {
            update(this, '/api/colors/' + this.urlId, this.getFormData('patch'), 'color', 'message')
        },

        storeData() {
            storage(this, '/api/colors/', this.getFormData(), 'color', 'message', 'urlId', 'colors')
        },

        getFormData(method = '') {
            var formData = new FormData();

            formData.append('name', this.color.name);
            formData.append('code', this.color.code);
            formData.append('brand_id', this.color.brand_id);

            if(this.sub_color)
                formData.append('web', this.color.web_main+':'+this.color.web_sub);
            else
                formData.append('web', this.color.web_main);

            if(method == 'patch')
                formData.append("_method", "PATCH");

            return formData;
        },
    },
    beforeRouteEnter(to, from, next) {
        next(vm => {
            vm.prevRoute = from;
        });
    },

}
</script>
