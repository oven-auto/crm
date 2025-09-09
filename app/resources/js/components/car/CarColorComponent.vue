<template>
<div class="row">
    <div class="col text-center">
        <div>{{currentColor.color.name}}</div>
        <div class="">
            <img :src="currentImg" style="display:inline-block; width: 300px;">
        </div>
        <div style="display:inline-block;" class=" text-center mx-2" v-for="item in colors">
            <div v-on:click="clickColor(item.id)">
                <div>{{item.color.code}}</div>
                <ColorIcon :colors="item.color.web" :current="currentColor.color.web"></ColorIcon>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import ColorIcon from '../html/ColorIcon';

export default {
    components: {
        ColorIcon
    },
    name: 'car-color',
    data() {
        return {
            colors: [],
            currentColor: {
                color: {
                    id: 0,
                    name: '',
                    web: '',
                    image: ''
                }
            }
        }
    },
    props: {
        complectation: {
            type: Number,
            default: 0,
        },
        currentColorId: {
            type: Number,
            default: 0
        }
    },
    computed: {
        currentImg() {
            return this.currentColor.image
        },
    },
    mounted() {
        this.loadData();
    },

    methods: {

        clickColor(id) {
            this.colors.forEach( (item) => {
                if(item.id == id)
                     this.currentColor = item;
            })
            this.$emit('updateColor', this.currentColor.id)
        },

        loadData() {
            axios.get('/api/services/html/color/mark?' + 'complectation_id='+this.complectation)
            .then( (res) => {
                this.colors = res.data.data
                this.clickColor(this.currentColorId)
            })
            .catch( (errors) => {

            })
        }
    },
    watch: {
        complectation(v){
            this.currentColor = {
                id: 0,
                color: {
                    id: 0,
                    name: '',
                    web: '',
                    image: ''
                }
            }
            this.loadData()
        }
    },



}
</script>
