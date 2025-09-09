<template>
    <b-modal ref="device-image-modal" hide-footer :title="device.name">
        <div class="d-block text-center">
            <Spinner v-if="loading"></Spinner>
            <div v-else>
                <img :src="image" style="width:100%;">
            </div>
        </div>
    </b-modal>
</template>

<script>
import Spinner from '../spinner/SpinBlockComponent'
export default {
    name: 'device-image-modal',
    components: {Spinner},
    data() {
        return {
            device: {
                id: '',
            },
            image: '',
            loading: true,
        }
    },
    methods: {
        loadData() {
            this.loading = true
            var tmp = ''
            if(this.device.id)
                tmp += 'id='+this.device.id
            axios.get('/api/services/images/devices?'+tmp)
            .then(res => {
                this.image = res.data.data
            }).catch( errors => {

            }).finally(() => {
                this.loading = false
            })
        }
    },
    watch: {
        'device.id'(old) {
            this.loadData()
        }
    }
}
</script>
