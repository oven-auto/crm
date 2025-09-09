<template>
    <div style="z-index: 999;border-top: 0px solid #ccc; position: -webkit-sticky; position: sticky;left: 0px; bottom: 0px; background:rgba(255,255,255,0.8);width: 100%; padding: 15px 0;">
        <div class="">
            <div class="row">
                <div class="col">
                    <button v-if="id" @click.prevent="$parent.updateData(id)" type="button" class="btn btn-success btn-block">
                        Изменить
                    </button>

                    <button v-else @click.prevent="$parent.storeData()" type="button" class="btn btn-success btn-block">
                        Создать
                    </button>
                </div>
                <div class="col">
                    <a class="btn btn-secondary btn-block" @click="$router.go(-1)">Назад</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'edit-controll',
    props: ['id'],
    mounted() {
        //console.log(this.$router)
    }
}
</script>
