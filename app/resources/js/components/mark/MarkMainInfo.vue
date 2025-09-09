<template>
<div>
    <div class="row pb-3">

        <div class="col-4 text-left">
            <div>
                <label class="checkbox " :title="'Статус'">
                    <input class="device-checkbox-toggle" type="checkbox" v-bind:value="mark.status" v-model="mark.status">
                    <div class="checkbox__text" style="">
                        <div style="width: 200px;text-align: left;">
                            {{ (mark.status) ? 'Модель актуальна' : 'Модель выключена' }}
                        </div>
                    </div>
                </label>
            </div>
        </div>
    </div>

    <div class="row pb-3">
        <div class="col-12 h5 mb-3">
            Основные сведения о модели
        </div>
        <div class="col-4">
            <div class="btn-group">
                <div style="width: 70%;">
                    <label for="name">Название</label>
                    <input style="border-radius: 5px 0 0 5px;" type="text" name="name" v-model="mark.name" class="form-control"/>
                </div>
                <div style="width: 30%;">
                    <label for="name">Префикс</label>
                    <input style="border-radius: 0px 5px 5px 0px;"
                        type="text"
                        name="prefix"
                        v-model="mark.prefix"
                        class="form-control"
                    />
                </div>
            </div>

            <BrandSelect v-model="mark.brand_id" name="brand_id"></BrandSelect>

            <BodySelect v-model="mark.body_work_id" name="body_work_id"></BodySelect>

            <CountrySelect v-model="mark.country_factory_id" name="country_factory_id"></CountrySelect>
        </div>

        <div class="col-8">
            <div class="">
                <label for="name">Слоган</label>
                <input type="text" v-model="mark.info.slogan" class="form-control">
            </div>

            <div class="">
                <label for="name">Описание</label>
                <textarea v-model="mark.info.description" class="form-control" style="height: 175px;"></textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <label class="checkbox " :title="'Статус'">
                <input class="device-checkbox-toggle" type="checkbox" v-bind:value="mark.show_driver" v-model="mark.show_driver">
                <div class="checkbox__text" style="">
                    <div style="width: 200px;text-align: left;">
                        {{ (mark.show_driver) ? 'Тип привода виден' : 'Тип привода спрятан' }}
                    </div>
                </div>
            </label>

            <label class="checkbox " :title="'Статус'">
                <input class="device-checkbox-toggle" type="checkbox" v-bind:value="mark.show_toxic" v-model="mark.show_toxic">
                <div class="checkbox__text" style="">
                    <div style="width: 200px;text-align: left;">
                        {{ (mark.show_toxic) ? 'Норма токсичности видна' : 'Норма токсичности спрятана' }}
                    </div>
                </div>
            </label>
        </div>
    </div>
</div>
</template>

<script>
import BrandSelect from '../html/BrandSelect';
import BodySelect from '../html/BodySelect';
import CountrySelect from '../html/CountrySelect';

export default {
    name: 'MarkInfo',
    components: {
        BrandSelect, BodySelect, CountrySelect,
    },
    props: {
        mark: {
            name: '',
            status: 0,
            show_driver: 0,
            show_toxic: 0,
            prefix: '',
            brand_id: 0,
            body_work_id: 0,
            country_factory_id: 0,
            info: {
                slogan: '',
                description: '',
            },
        }
    }
}
</script>
