<template>
<div class="" v-if="marks">
    <span v-for="(itemMark,i) in marks" :key="'checkboxmark'+i" >
        <label class="checkbox d-flex align-items-center" :title="itemMark.name">
            <input
                class="device-checkbox-toggle"
                type="checkbox"
                v-bind:value="itemMark.id"
                v-model="selected"
                @change="changeMark"
            >
            <div class="checkbox__text" style="overflow:hidden">
                {{itemMark.name}}
            </div>
        </label>
    </span>
</div>
</template>

<script>
export default {
    name: 'mark-group-check-box',
    props: ['brand','install'],
    data() {
        return {
            marks: [],
            selected: []
        }
    },
    methods: {
        changeMark() {
            this.$emit('checkMark', {
                marks: this.selected
            })
        },

        getMarks() {
            var param = 'brand_id='+this.brand
            axios.get('/api/marks?' + param)
            .then(res => {
                this.marks = res.data.data
                this.selected = this.install
            })
            .catch(errors => {

            })
        },
    },

    watch: {
        brand: function(val) {
            this.getMarks()
        },
        install: function(val) {
            this.selected = this.install
        }
    }
}
</script>
