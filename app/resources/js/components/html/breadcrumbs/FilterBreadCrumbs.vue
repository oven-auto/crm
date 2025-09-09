<template>
<div class="text-right pt-3" v-if="!emptySearch">
    Параметры поиска:
    <span class=" ml-1 badge badge-search pointer" v-for="(item,i) in crumbs" @click="deleteCrumbs(i)" :key="'crumbs'+i">
        {{item}} ✖
    </span>
</div>
</template>

<script>
export default {
    data() {
        return {
            crumbs: {}
        }
    },

    computed: {
        emptySearch() {
            var mas = this.search
            delete mas.page
            return isEmptyObject(mas)
        }
    },

    props: ['search','type'],

    methods: {
        deleteCrumbs(i) {
            this.search[i] = '';
            this.$emit('updateParent', this.search)
        },

        getTitleFilter() {
            axios.get('/api/breadcrumbs/' + this.type + '/title?' + this.searchToUrl())
            .then( res => {
                this.crumbs = res.data.data
            }).catch( errors => {

            }).finally( () => {

            })
        },
        searchToUrl() {
            var mas = this.search;
            var str = '';
            var objUrl = {}
            for(var key in mas)
                if(mas[key]) {
                    str+=key+'='+mas[key]+'&';
                    objUrl[key] = mas[key]
                }
            return str;
        },
    },
    updated() {

    },
    watch: {
        search: {
            handler(val){
                this.getTitleFilter()
            },
            deep: true
        }
    }
}
</script>
