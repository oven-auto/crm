<template>
    <div class="alert alert-warning alert-dismissible fade show" role="alert" style="position:fixed;top:100px;right:0px;z-index:9999;">
        <strong> {{ message }} </strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>



</template>

<script>
export default {
    name: 'message',
    props: {
        message: {
            type: String,
            default: null,
        }
    },
    mounted() {

    }
}
</script>
