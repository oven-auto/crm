<template>
    <span
        class="badge status-badge"
        v-bind:class="{'badge-success': current, 'badge-dark':!current}"
        v-on:click="changeStatus"
    >
        <span v-if="loading" class="badge">
            <div class="spinner-border spinner-border-sm text-light" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </span>
        <span v-else>
            {{statusStr}}
        </span>
    </span>
</template>

<script>
export default {
    name: 'complectation-status',
    props: ['id','value'],
    data() {
        return {
            status: 0,
            loading: false,
        }
    },
    mounted() {
        this.status = this.current
    },
    computed: {
        statusStr() {
            return this.current ? 'Актуальная' : 'Архивная';
        },
        current: {
            get() {
                return this.value;
            },
            set(val) {
                this.status = val
                this.$emit('input',val)
            },
        }
    },
    methods: {
        changeStatus() {
            this.loading = true
            this.current = Math.abs(Number(this.value)-1)
            axios.patch('/api/complectations/' + this.id, {status: this.status}, this.getConfig())
            .then(res => {
                if(res.data.status == 1) {

                } else {

                }
            })
            .catch(errors => {
                console.log(errors)
            })
            .finally(() => {
                this.loading = false
            })
        },
        getConfig() {
            return {
                'content-type': 'application-json'
            }
        },
    }
}
</script>

<style scoped>
.status-badge {
    width: 80px;
    cursor: pointer;
}
.status-badge .spinner-border-sm{
    width: 0.7rem; height: 0.7rem !important;
}
</style>
