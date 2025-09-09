<template>
<div class="row" v-if="users">
    <div class="col-12">
        <label>{{label ? label : 'Получатели' }}</label>
    </div>
    <div v-for="(item,i) in users" :key="'checkboxuser'+i" class="col-12">
        <label class="checkbox d-flex align-items-center" :title="item.name">
            <input
                class="device-checkbox-toggle"
                type="checkbox"
                v-bind:value="item.id"
                v-model="selected"
                @change="changeUser"
            >
            <div class="checkbox__text" style="overflow:hidden">
                {{item.name}}
                ({{item.email}})
            </div>
        </label>
    </div>
</div>
</template>

<script>
export default {
    name: 'user-group-check-box',
    props: ['install','value','label'],
    data() {
        return {
            users: [],
            selected: []
        }
    },

    mounted() {
        this.loadUsers()
    },

    methods: {
        changeUser() {
            this.$emit('checkUsers', {
                users: this.selected
            })
        },

        loadUsers(){
            axios.get('/api/services/html/select/users')
            .then(res => {
                this.users = res.data.data
                this.selected = this.value
            }).catch(error => {

            }).finally( ()=> {

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
