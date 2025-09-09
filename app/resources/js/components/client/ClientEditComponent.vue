<template>
    <div class="client-edit">
        <spin v-if="loading && urlId"></spin>

        <div v-else>
            <form>
                <div class="h5">
                    {{ client.firstname ? client.lastname+' '+client.firstname+' '+client.fathername : 'Новый клиент' }}
                </div>

                <div class="row">
                    <div class="col">
                        <Vinput v-model="client.lastname" :label="'Фамилия'"></Vinput>
                    </div>

                    <div class="col">
                        <Vinput v-model="client.firstname" :label="'Имя'"></Vinput>
                    </div>

                    <div class="col">
                        <Vinput v-model="client.fathername" :label="'Отчество'"></Vinput>
                    </div>
                </div>

                <div class="row">

                </div>

                <div class="row">
                    <div class="col-3">
                        <Vinput v-model="client.lastname" :label="'Зона контакта'"></Vinput>
                    </div>

                    <div class="col-6">
                        <Vinput v-model="client.lastname" :label="'Прописка'"></Vinput>
                    </div>

                    <div class="col-3">
                        <Vinput v-model="client.lastname" :label="'Дата рождения'"></Vinput>
                    </div>
                </div>

                <div class="row pb-3">
                    <div class="col">
                        <Vinput v-model="client.lastname" :label="'Паспорт'"></Vinput>
                    </div>

                    <div class="col">
                        <Vinput v-model="client.lastname" :label="'Дата выдачи'"></Vinput>
                    </div>

                    <div class="col">
                        <Vinput v-model="client.lastname" :label="'Водительское'"></Vinput>
                    </div>

                    <div class="col">
                        <Vinput v-model="client.lastname" :label="'Дата выдачи'"></Vinput>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div >
                            <label for="name">Email</label>
                            <input type="text" name="name" v-model="client.email" class="form-control"/>
                        </div>

                        <div >
                            <label for="name">Телефон</label>
                            <input type="text" name="name" v-model="client.phone" class="form-control"/>
                        </div>
                    </div>
                </div>
            </form>

            <FormControll :id="urlId"></FormControll>

        </div>
    </div>
</template>

<script>
import Spin from '../spinner/SpinComponent';
import Vinput from '../html/TextInput';

export default {
    name: 'client-edit',
    components: {Spin, Vinput},
    data() {
        return {
            client: {},
            loading: true,
            urlId: this.$route.params.id,
            message: '',
            previusPage: '/'
        }
    },
    mounted() {
        this.previusPage = this.prevRoute.fullPath
        if(this.urlId)
            this.loadData()
    },
    methods: {
        loadData() {
            edit(this, '/api/clients/' + this.urlId + '/edit', 'client', 'message')
        },

        updateData(id) {
            update(this, '/api/clients/' + this.urlId, this.getFormData('patch'), 'client', 'message')
        },

        storeData() {
            storage(this, '/api/clients/', this.getFormData(), 'client', 'message', 'urlId', 'clients')
        },

        getFormData(method = '') {
            var formData = new FormData();
            formData.append('firstname', this.client.firstname);
            formData.append('lastname', this.client.lastname);
            formData.append('fathername', this.client.fathername);
            formData.append('email', this.client.email);
            formData.append('phone', this.client.phone);

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
