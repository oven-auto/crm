<template>
    <div class="color-icon" :style="{ background: bg}" :class="(current == colors) ? 'checked-color-icon' : ''">

    </div>
</template>

<script>
export default {
    name: 'color-icon',
    data() {
        return {
            background: '',
        }
    },
    mounted() {
        /*var colorArr = this.colors.split(":");
        if(colorArr.length > 1)
            this.background = 'linear-gradient(to top, '+colorArr[0]+' 50%, '+colorArr[1]+' 50%)';
        else
            this.background = colorArr[0];*/
    },
    props: ['colors', 'current'],
    computed: {
        bg() {
            var back = ''
            var colorArr = this.colors.split(",");
            if(colorArr.length > 1)
                back = 'linear-gradient(to top, '+colorArr[0]+' 50%, '+colorArr[1]+' 50%)';
            else
                back = colorArr[0];
            return back
        }
    }
}
</script>

<style scoped>
.color-icon{
    width: 30px;
    height: 30px;
    border-radius: 100%;
    box-shadow: 0 0 5px #ccc;
    display: inline-block;
    cursor: pointer;
}
.color-icon:hover{
    transform: scale(1.1, 1.1);
}
.checked-color-icon{
    border: 5px solid #fff;
    box-shadow: 0 0 5px #000;
}
</style>
