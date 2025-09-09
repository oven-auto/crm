<template>
<div>
	<div class="row" v-if="controlls">
        <div class="col-12">
            <label>{{label ? label : 'Реквизиты формы' }}</label>
        </div>
	</div>

    <div v-for="(item,i) in controlls" :key="'checkboxcontroll'+i" class="row">
        <div class="col-6">
            {{item.name}}
        </div>

        <div class="col-6">
                <div class="px-0" v-for="element in item.elements">
                    <label class="checkbox d-flex align-items-center" :title="element.name">
                        <input
                            class="device-checkbox-toggle"
                            type="checkbox"
                            v-bind:value="element.id"
                            v-model="selected"
                            @change="changeControll"
                        >
                        <div class="checkbox__text" style="overflow:hidden">
                            {{element.title}}
                        </div>
                    </label>
                </div>
        </div>
	</div>
</div>
</template>

<script>
export default {
    name: 'form-controll-group-check-box',
    props: ['install','value','label'],
    data() {
        return {
            controlls: [],
            selected: []
        }
    },

    mounted() {
        this.loadControlls()
    },

    methods: {
        changeControll() {
            this.$emit('checkControlls', {
                controlls: this.selected
            })
        },

        loadControlls(){
            this.loading = true
            axios.get('/api/forms/controlls')
            .then(res => {
                this.controlls = res.data.data
            }).catch(errors => {

            }).finally(() => {
                this.loading = false
            })
        }
    },

    watch:{
        value: function(val) {
            this.selected = this.value
        }
    }
}
</script>
