<template>
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="http://192.168.1.98:8280/storage/system/logo.png" style="height: 40px;">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Справочники
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                        <router-link :to="'/brands/list'" class="dropdown-item">БрендыSSSddd999</router-link>

                        <div class="dropdown-divider"></div>

                        <router-link :to="'/devices/list'" class="dropdown-item">Оборудование</router-link>
                        <router-link :to="'/devicetypes/list'" class="dropdown-item">Категории оборудования</router-link>
                        <router-link :to="'/devicefilters/list'" class="dropdown-item">Фильтры по оборудованию</router-link>

                        <div class="dropdown-divider"></div>

                        <router-link :to="'/motortypes/list'" class="dropdown-item">Типы моторов</router-link>
                        <router-link :to="'/motortransmissions/list'" class="dropdown-item">Типы трансмиссий</router-link>
                        <router-link :to="'/motordrivers/list'" class="dropdown-item">Типы приводов</router-link>
                        <router-link :to="'/motors/list'" class="dropdown-item">Агрегаты</router-link>

                        <div class="dropdown-divider"></div>

                        <router-link :to="'/properties/list'" class="dropdown-item">Характеристики</router-link>

                        <div class="dropdown-divider"></div>

                        <router-link :to="'/colors/list'" class="dropdown-item">Палитра цветов</router-link>

                        <div class="dropdown-divider"></div>

                        <router-link :to="'/bodyworks/list'" class="dropdown-item">Типы кузовов</router-link>

                         <div class="dropdown-divider"></div>

                        <router-link :to="'/countryfactories/list'" class="dropdown-item">Производители</router-link>

                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Прайс
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <router-link :to="'/marks/list'" class="dropdown-item">Модели</router-link>
                        <div class="dropdown-divider"></div>
                        <router-link :to="'/packs/list'" class="dropdown-item">Опции</router-link>
                        <div class="dropdown-divider"></div>
                        <router-link :to="'/complectations/list'" class="dropdown-item">Комплектации</router-link>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Склад
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <router-link :to="'/cars/list/1'" class="dropdown-item">Актуальный</router-link>
                        <router-link :to="'/cars/list/archive/1'" class="dropdown-item">Архивный</router-link>
                    </div>
                </li>

                <li class="nav-item">
                    <router-link :to="'/credits/list'" class="nav-link">Кредиты</router-link>
                </li>

                <li class="nav-item">
                    <router-link :to="'/banners/list'" class="nav-link">Банеры</router-link>
                </li>

                <li class="nav-item">
                    <router-link :to="'/shortcuts/list'" class="nav-link">Ярлыки</router-link>
                </li>

                <li class="nav-item">
                    <router-link :to="'/sectionpages/list'" class="nav-link">Разделы</router-link>
                </li>

                <li class="nav-item">
                    <router-link :to="'/pages/list'" class="nav-link">Страницы</router-link>
                </li>

                <li class="nav-item">
                    <router-link :to="'/forms/list'" class="nav-link">Формы</router-link>
                </li>

                <li class="nav-item">
                    <router-link :to="'/clients/list'" class="nav-link">База клиентов</router-link>
                </li>

                <!-- <li v-for="link in links" class="nav-item {{ item.href ? '' : 'dropdown' }}">
                    <router-link :to="link.href" class="nav-link"> {{ link.title }}</router-link>
                </li> -->
            </ul>

            <ul class="navbar-nav ml-auto">
                <li><span class="nav-link" @click="login">Вход</span></li>
                <li><span class="nav-link" @click="logout">Выход</span></li>
            </ul>
        </div>
    </div>
</nav>
</template>

<script>
export default {
    name: 'top-menu',
    data() {
        return {
            links: [

            ]
        }
    },
    methods: {
        login(){
            window.location.href = '/login';
        },

        logout() {
            axios.get('/api/exit')
            .then(res => {
                window.location.href = '/login';
            }).catch(errors => {

            }).finally(() => {

            })
        },
    }
}
</script>
