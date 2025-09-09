<template>

        <div>
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <div class="h4">Овен-Авто</div>
        </div>

</template>

<script>
export default {
    name: 'spin-block',
}
</script>

<style scoped>
.spinner-wraper{
    width: 100%;
    height: 100vh;
    background: #fff;
    position: fixed;
    top: 0px;
    left: 0px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 999;
}
</style>
