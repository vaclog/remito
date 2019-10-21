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


import BootstrapVue from 'bootstrap-vue'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.use(BootstrapVue)




window.Vue.use(VueRouter);
import VueAxios from 'vue-axios';
import axios from 'axios';

import App from './App.vue';

Vue.use(VueAxios, axios);








import About from './components/about.vue'

import vuetify from './plugins/vuetify';
Vue.use(vuetify)

import create from './components/homeComponent.vue';
Vue.component('home', require('./components/homeComponent.vue').default);


const routes = [



    {
        name: 'about',
        path: '/about',
        component: About
    },
    {
        name: 'create',
        path: '/remitos/create',
        component: create
    }
];



const router = new VueRouter({ mode: 'history', base: process.env.BASE_URL, routes: routes })


Vue.component('index-item', require('./components/file/itemIndexComponent.vue').default)



Vue.component('upload-component', require('./components/file/uploadComponent.vue').default);
Vue.component('select-client', require('./components/file/selectClientComponent.vue').default);


Vue.component('transport-data', require('./components/file/transportComponent.vue').default);

Vue.component('remito-index', require('./components/remito/index.vue').default);

Vue.component('show', require('./components/remito/Show.vue').default);


Vue.component('index_client', require('./components/client/ClientIndex.vue'))


Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);



const store = new Vuex.Store({
    state: {
        count: 11,
        articulos: {},
        customer: [],
        numero_remito: 0,
        fecha_remito: null,
        idcliente: null,
        pedido: null,

    },
    getters: {
        customerSel: state => {
            return state.customer;
        }
    },
    mutations: {
        increment(state) {
            state.count++
        },
        setClientId(state, array) {
            state.idcliente = array;
        },
        setArticulos(state, array) {
            state.articulos = array;
        },
        setCustomer(state, array) {
            state.customer = array
        },

        setNumeroRemito(state, numero_remito) {
            state.numero_remito = numero_remito;
        },
        setFechaRemito(state, fecha) {
            state.fecha_remito = fecha;
        },
        setPedido(state, pedido) {
            state.pedido = pedido;
        }



    }
})

const app = new Vue(
    Vue.util.extend({ router, store, vuetify },

        //App
        // ClientIndex
    ),


).$mount('#app')