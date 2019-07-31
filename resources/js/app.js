/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import '@mdi/font/css/materialdesignicons.css' // Ensure you are using css-loader


window.Vue = require('vue');
import VueRouter from 'vue-router';
import Vuex from "vuex"




window.Vue.use(VueRouter);
import VueAxios from 'vue-axios';
import axios from 'axios';

import App from './App.vue';

Vue.use(VueAxios, axios);






import ClientIndex from './components/client/ClientIndex.vue';
import ClientCreate from './components/client/ClientCreate.vue';
import ClientEdit from './components/client/ClientEdit.vue';
import ClientHome from './components/client/ClientHome.vue'

import About from './components/about.vue'

import vuetify from './plugins/vuetify';
Vue.use(vuetify)



const routes = [

    {
        name: 'home',
        path: '/',
        component: ClientHome
    },
    {
        name: 'create',
        path: '/create',
        component: ClientCreate
    },
    {
        name: 'clients',
        path: '/clients',
        component: ClientIndex
    },
    {
        name: 'edit',
        path: '/edit/:id',
        component: ClientEdit
    },
    {
        name: 'about',
        path: '/about',
        component: About
    }
];



const router = new VueRouter({ mode: 'history', base: process.env.BASE_URL, routes: routes })


Vue.component('index-item', require('./components/file/itemIndexComponent.vue').default)



Vue.component('upload-component', require('./components/file/uploadComponent.vue').default);
Vue.component('select-client', require('./components/file/selectClientComponent.vue').default);


Vue.component('transport-data', require('./components/file/transportComponent.vue').default);
Vue.component('home', require('./components/homeComponent.vue').default);

Vue.component('index_client', require('./components/client/ClientIndex.vue'))






const store = new Vuex.Store({
    state: {
        count: 11,
        articulos: {},
        customer: []

    },
    mutations: {
        increment(state) {
            state.count++
        },
        setArticulos(state, array) {
            state.articulos = array;
        },
        setCustomer(state, array) {
            state.customer = array
        }

    }
})

const app = new Vue(
    Vue.util.extend({ router, store, vuetify },

        //App
        // ClientIndex
    ),


).$mount('#app')