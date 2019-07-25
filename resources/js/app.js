/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router';

window.Vue.use(VueRouter);
import VueAxios from 'vue-axios';
import axios from 'axios';

import App from './App.vue';

Vue.use(VueAxios, axios);


import ClientIndex from './components/client/ClientIndex.vue';
import ClientCreate from './components/client/ClientCreate.vue';
import ClientEdit from './components/client/ClientEdit.vue';
import ClientHome from './components/client/ClientHome.vue'

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
    }
];



const router = new VueRouter({ mode: 'history', routes: routes })



Vue.component('upload-component', require('./components/file/uploadComponent.vue'));
Vue.component('index_client', require('./components/client/ClientIndex.vue'))


const app = new Vue(
    Vue.util.extend({ router },
        //App
        // ClientIndex
    )

).$mount('#app')


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/*const app = new Vue({
    el: '#app',
});
*/