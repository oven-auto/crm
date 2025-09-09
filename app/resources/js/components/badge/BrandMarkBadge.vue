<template>
<div>
    <div class="btn-group" role="group" aria-label="Basic example" v-if="block!=1">
        <span class="badge" :style="{background: brand.brand_color, color: brand.font_color}" style="border-radius: 0.25rem 0 0 0.25rem;">
            {{brand.name}}
        </span>
        <span class="badge badge-dark" style="border-radius: 0 0.25rem 0.25rem 0;">
            {{mark}}
        </span>
    </div>

    <div v-if="block==1">
        <span class="badge" :style="{background: brand.brand_color, color: brand.font_color}" style="border-radius: 0.25rem;">
            {{brand.name}}
        </span>
        <br/>
        <span class="badge badge-dark" style="border-radius: 0.25rem;">
            {{mark}}
        </span>
    </div>
</div>
</template>

<script>
export default {
    name: 'brand-badge',
    props: ['brand','mark','block'],
}
</script>
