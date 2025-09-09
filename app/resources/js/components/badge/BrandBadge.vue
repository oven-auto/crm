<template>
    <span class="badge" :style="{background: brand.brand_color, color: brand.font_color}">
        {{brand.name}}
    </span>
</template>

<script>
export default {
    name: 'brand-badge',
    props: ['brand'],
}
</script>
