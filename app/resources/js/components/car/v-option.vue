<template>
    <div>
        <div @click="eventOpen()">{{ title }}</div>
        <div v-if="isOpen">
            <div v-for="option in options" :key="'opt'+option.id" @click="eventClick(option)">
                {{ option.name }}
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: 'SelectCustomInner',
    props: ['value', 'options',],
    data() {
        return {
            isOpen: false,
        }
    },
    computed: {
        selected: {
            get() {
                return this.value;
            },
            set(val) {
                this.$emit('input', val);
            },
        },
        title() {
            var tmp = this.options.find(x=> x.id === this.selected)
            if(tmp)
                return tmp.name
        }
    },

    methods: {
        eventClick(obj) {
            this.selected = obj.id
            this.isOpen = false
        },
        eventOpen() {
            this.isOpen = !this.isOpen
        }
    }
}

</script>
