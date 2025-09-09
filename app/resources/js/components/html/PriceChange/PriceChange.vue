<template>
<div>
    <div @click="showPriceModal(id)" v-if="statusPriceClick == false" style="width:120px;">
        {{ formatPrice(selected) }}
    </div>
    <div class="input-group" v-else style="width:120px;">
        <input type="text" class="p-0" v-model="selected" aria-describedby="basic-addon2" onFocus="this.select()"
        style="display:block;width:60%;font-size: 14px;line-height: 10px;">
        <div class="input-group-append">
            <button class="btn btn-secondary input-group-text p-0 px-1" style="" @click="changePrice">OK</button>
        </div>
    </div>
</div>
</template>

<script>
export default {
    data() {
        return{
            statusPriceClick: false,
        }
    },
    props: ['id','value','url','current'],
    computed: {
        selected: {
            get() {
                return this.value;
            },
            set(val) {
                this.$emit('input', val);
            },
        },
    },
    methods: {
        showPriceModal(id) {
            this.statusPriceClick = true
        },

        changePrice(complectation) {
            var data = {
                id: this.id,
                price: this.selected
            }
            axios.patch(this.url, data, this.getConfig())
            .then(res => {
                this.$emit('priceSend')
            }).catch(errors => {

            }).finally(()=>{
                this.statusPriceClick = false
            })

        },

        formatPrice(price) {
            return number_format(price,0,'',' ','руб.')
        },

        getConfig() {
            return {
                'content-type': 'application-json'
            }
        },
    },
}
</script>
