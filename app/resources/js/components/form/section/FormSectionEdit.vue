<template>
<div>
    <div class="row pb-3">
        <div class="col-6">
            <BrandList v-model="formsection.brand_id"></BrandList>
            <InputBox v-model="formsection.name" :label="'Название'"></InputBox>
        </div>
    </div>
    <button v-if="urlId" @click.prevent="updateData(urlId)" type="button" class="btn btn-success">
        Изменить
    </button>

    <button v-else @click.prevent="storeData()" type="button" class="btn btn-success">
        Создать
    </button>

    <a class="btn btn-secondary" @click="$router.go(-1)">Назад</a>
</div>
</template>

<script>
import BrandList from '../../html/BrandSelect';
import InputBox from '../../html/TextInput';

export default {
    name: 'form-section-edit',
    components: {BrandList, InputBox},
    data() {
        return {
            formsection: {
                brand_id: 0,
                name: '',
                parent_id: 0,
                id: 0,
            },
            urlId: this.$route.params.id,
        }
    },
    methods:{
        loadData(){
            axios.get('/api/forms/sections/'+this.urlId)
            .then(res => {
                this.formsection =res.data.data
            }).catch(errors => {

            }).finally(() => {

            })
        },

        updateData(){
            var url = '/api/forms/sections/'+this.urlId;
            axios.put(url, this.formsection)
            .then(res => {

            }).catch(errors => {

            }).finally(() => {

            })
        },

        storeData() {
            var url = '/api/forms/sections';
            axios.post(url, this.formsection)
            .then(res => {

            }).catch(errors => {

            }).finally(() => {

            })
        }
    },

    mounted() {
        if(this.$route.query.brand_id)
            this.formsection.brand_id = this.$route.query.brand_id
        if(this.$route.query.parent_id)
            this.formsection.parent_id = this.$route.query.parent_id
        if(this.urlId)
            this.loadData(this.urlId)
    }
}
</script>
