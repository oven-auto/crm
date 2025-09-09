import vueRouter from 'vue-router';
import Vue from 'vue';

Vue.use(vueRouter);

import brandList from './components/brand/BrandListComponent';
import brandEdit from './components/brand/BrandEditComponent';

import deviceTypesList from './components/device/type/TypeListComponent';
import deviceTypesEdit from './components/device/type/TypeEditComponent';

import deviceFilterList from './components/device/filter/FilterListComponent';
import deviceFilterEdit from './components/device/filter/FilterEditComponent';

import deviceList from './components/device/DeviceListComponent';
import deviceEdit from './components/device/DeviceEditComponent';

import propertyList from './components/property/PropertyListComponent';
import propertyEdit from './components/property/PropertyEditComponent';

import motorTypeList from './components/motor/type/TypeListComponent';
import motorTypeEdit from './components/motor/type/TypeEditComponent';

import motorTransmissionList from './components/motor/transmission/TransmissionListComponent';
import motorTransmissionEdit from './components/motor/transmission/TransmissionEditComponent';

import motorDriverList from './components/motor/driver/DriverListComponent';
import motorDriverEdit from './components/motor/driver/DriverEditComponent';

import motorList from './components/motor/MotorListComponent';
import motorEdit from './components/motor/MotorEditComponent';

import colorList from './components/color/ColorListComponent';
import colorEdit from './components/color/ColorEditComponent';

import packList from './components/pack/PackListComponent';
import packEdit from './components/pack/PackEditComponent';

import bodyWorkList from './components/mark/bodywork/BodyWorkListComponent';
import bodyWorkEdit from './components/mark/bodywork/BodyWorkEditComponent';

import countryFactoryList from './components/mark/country/CountryFactoryListComponent';
import countryFactoryEdit from './components/mark/country/CountryFactoryEditComponent';

import markList from './components/mark/MarkListComponent';
import markEdit from './components/mark/MarkEditComponent';

import complectationList from './components/complectation/ComplectationListComponent';
import complectationEdit from './components/complectation/ComplectationEditComponent';

import carList from './components/car/CarListComponent';
import carEdit from './components/car/CarEditComponent';

import creditList from './components/credit/CreditListComponent';
import creditEdit from './components/credit/CreditEditComponent';

import bannerList from './components/banner/BannerListComponent';
import bannerEdit from './components/banner/BannerEditComponent';

import shortcutList from './components/shortcut/ShortcutListComponent';
import shortcutEdit from './components/shortcut/ShortcutEditComponent';

import sectionPageList from './components/page/SectionPageListComponent';
import sectionPageEdit from './components/page/SectionPageEditComponent';

import pageList from './components/page/PageListComponent';
import pageEdit from './components/page/PageEditComponent';

import carArchiveList from './components/cararchive/CarArchiveList';
import carArchiveShow from './components/cararchive/CarArchiveShow';

import FormSectionList from './components/form/section/FormSectionList';
import FormSectionEdit from './components/form/section/FormSectionEdit';
import FormEdit from './components/form/section/FormEdit';

import ClientList from './components/client/ClientListComponent';
import ClientEdit from './components/client/ClientEditComponent';

const routes = [
    {
        path: '/',
        component: brandList
    },

    //////////
    //BRANDS//////////////////////////////////////////////////////////////////
    //////////
    {
        path: '/brands/list',
        component: brandList
    },
    {
        path: '/brands/create',
        component: brandEdit
    },
    {
        path: '/brands/edit/:id',
        component: brandEdit
    },
    ///////////////
    //DEVICE TYPE/////////////////////////////////////////////////////////////
    ///////////////
    {
        path: '/devicetypes/list',
        component: deviceTypesList
    },
    {
        path: '/devicetypes/create',
        component: deviceTypesEdit
    },
    {
        path: '/devicetypes/edit/:id',
        component: deviceTypesEdit
    },
    /////////////////
    //DEVICE FILTER////////////////////////////////////////////////////////////
    /////////////////
    {
        path: '/devicefilters/list',
        component: deviceFilterList
    },
    {
        path: '/devicefilters/create',
        component: deviceFilterEdit
    },
    {
        path: '/devicefilters/edit/:id',
        component: deviceFilterEdit
    },
    ///////////
    //DEVICES//////////////////////////////////////////////////////////////////////
    ///////////
    {
        path: '/devices/list',
        component: deviceList
    },
    {
        path: '/devices/create',
        component: deviceEdit
    },
    {
        path: '/devices/edit/:id',
        component: deviceEdit
    },
    ///////////
    //PROPERTIES//////////////////////////////////////////////////////////////////////
    ///////////
    {
        path: '/properties/list',
        component: propertyList
    },
    {
        path: '/properties/create',
        component: propertyEdit
    },
    {
        path: '/properties/edit/:id',
        component: propertyEdit
    },
    ///////////
    //MOTOR TYPES//////////////////////////////////////////////////////////////////////
    ///////////
    {
        path: '/motortypes/list',
        component: motorTypeList
    },
    {
        path: '/motortypes/create',
        component: motorTypeEdit
    },
    {
        path: '/motortypes/edit/:id',
        component: motorTypeEdit
    },
    ///////////
    //MOTOR TRANSMISSION//////////////////////////////////////////////////////////////////////
    ///////////
    {
        path: '/motortransmissions/list',
        component: motorTransmissionList
    },
    {
        path: '/motortransmissions/create',
        component: motorTransmissionEdit
    },
    {
        path: '/motortransmissions/edit/:id',
        component: motorTransmissionEdit
    },
    ///////////
    //MOTOR DRIVER//////////////////////////////////////////////////////////////////////
    ///////////
    {
        path: '/motordrivers/list',
        component: motorDriverList
    },
    {
        path: '/motordrivers/create',
        component: motorDriverEdit
    },
    {
        path: '/motordrivers/edit/:id',
        component: motorDriverEdit
    },
    ///////////
    //MOTOR ///////////////////////////////////////////////////////////////////////////////
    ///////////
    {
        path: '/motors/list',
        component: motorList
    },
    {
        path: '/motors/create',
        component: motorEdit
    },
    {
        path: '/motors/edit/:id',
        component: motorEdit
    },
    ///////////
    //COLOR ///////////////////////////////////////////////////////////////////////////////
    ///////////
    {
        path: '/colors/list',
        component: colorList
    },
    {
        path: '/colors/create',
        component: colorEdit
    },
    {
        path: '/colors/edit/:id',
        component: colorEdit
    },
    ///////////
    //PACK   ///////////////////////////////////////////////////////////////////////////////
    ///////////
    {
        path: '/packs/list',
        component: packList
    },
    {
        path: '/packs/create',
        component: packEdit
    },
    {
        path: '/packs/edit/:id',
        component: packEdit
    },
    ///////////
    //BODY WORK   ///////////////////////////////////////////////////////////////////////////////
    ///////////
    {
        path: '/bodyworks/list',
        component: bodyWorkList
    },
    {
        path: '/bodyworks/create',
        component: bodyWorkEdit
    },
    {
        path: '/bodyworks/edit/:id',
        component: bodyWorkEdit
    },
    ///////////
    //COUNTRY FACTORY ///////////////////////////////////////////////////////////////////////////////
    ///////////
    {
        path: '/countryfactories/list',
        component: countryFactoryList
    },
    {
        path: '/countryfactories/create',
        component: countryFactoryEdit
    },
    {
        path: '/countryfactories/edit/:id',
        component: countryFactoryEdit
    },
    ///////////
    //MARK ///////////////////////////////////////////////////////////////////////////////////////////
    ///////////
    {
        path: '/marks/list',
        component: markList
    },
    {
        path: '/marks/create',
        component: markEdit
    },
    {
        path: '/marks/edit/:id',
        component: markEdit
    },
    ///////////
    //COMPLECTATION ///////////////////////////////////////////////////////////////////////////////////////////
    ///////////
    {
        path: '/complectations/list',
        component: complectationList
    },
    {
        path: '/complectations/create',
        component: complectationEdit
    },
    {
        path: '/complectations/edit/:id',
        component: complectationEdit
    },
    ///////////
    //CAR           ///////////////////////////////////////////////////////////////////////////////////////////
    ///////////
    {
        path: '/cars/list/:page?',
        component:carList
    },
    {
        path: '/cars/create',
        component: carEdit
    },
    {
        path: '/cars/edit/:id',
        component: carEdit
    },
    ///////////
    //ARCHIVE CAR           ///////////////////////////////////////////////////////////////////////////////////////////
    ///////////
    {
        path: '/cars/list/archive/:page?',
        component:carArchiveList
    },
    {
        path: '/cars/archive/:id?',
        component: carArchiveShow
    },
    ///////////
    //CREDIT    ///////////////////////////////////////////////////////////////////////////////////////////
    ///////////
    {
        path: '/credits/list',
        component: creditList
    },
    {
        path: '/credits/create',
        component: creditEdit
    },
    {
        path: '/credits/edit/:id',
        component: creditEdit
    },
    ///////////
    //BANNER    ///////////////////////////////////////////////////////////////////////////////////////////
    ///////////
    {
        path: '/banners/list',
        component: bannerList
    },
    {
        path: '/banners/create',
        component: bannerEdit
    },
    {
        path: '/banners/edit/:id',
        component: bannerEdit
    },

    ///////////
    //SHORTCUT    ///////////////////////////////////////////////////////////////////////////////////////////
    ///////////
    {
        path: '/shortcuts/list',
        component: shortcutList
    },
    {
        path: '/shortcuts/create',
        component: shortcutEdit
    },
    {
        path: '/shortcuts/edit/:id',
        component: shortcutEdit
    },

    ///////////
    //SECTION PAGE    ///////////////////////////////////////////////////////////////////////////////////////////
    ///////////
    {
        path: '/sectionpages/list',
        component: sectionPageList
    },
    {
        path: '/sectionpages/create',
        component: sectionPageEdit
    },
    {
        path: '/sectionpages/edit/:id',
        component: sectionPageEdit
    },

    ///////////
    //PAGE    ///////////////////////////////////////////////////////////////////////////////////////////
    ///////////
    {
        path: '/pages/list',
        component: pageList
    },
    {
        path: '/pages/create',
        component: pageEdit
    },
    {
        path: '/pages/edit/:id',
        component: pageEdit
    },
    ///////////
    //FORMSECTION    ///////////////////////////////////////////////////////////////////////////////////////////
    ///////////
    {
        path: '/forms/list',
        component: FormSectionList
    },
    {
        path: '/forms/create',
        component: FormSectionEdit
    },
    {
        path: '/forms/edit/:id',
        component: FormSectionEdit
    },
    {
        path: '/forms/formcreate',
        component: FormEdit
    },
    {
        path: '/forms/formedit/:id',
        component: FormEdit
    },

    ///////////
    //CLIENT CRUD   ///////////////////////////////////////////////////////////////////////////////////////////
    ///////////
    {
        path: '/clients/list',
        component: ClientList
    },
    {
        path: '/clients/create',
        component: ClientEdit
    },
    {
        path: '/clients/edit/:id',
        component: ClientEdit
    },






];

export default new vueRouter({
    mode: "history",
    routes: routes
});
