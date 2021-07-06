/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import _ from "lodash";
import Vuetify from "vuetify";
import { InertiaApp } from "@inertiajs/inertia-vue";
import Swal from "sweetalert2";
import VueI18n from "vue-i18n";

import "vuetify/dist/vuetify.min.css";
import Vue from "vue";

import translations from './I18n';

window.Vue = require('vue').default;

Vue.use(InertiaApp);
Vue.use(Vuetify);
Vue.prototype.$_ = _;
Vue.mixin({ methods: { route } });

require("./filters");

const Toast = Swal.mixin({
    toast: true,
    position: "bottom",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: toast => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    }
});
window.Toast = Toast;

// Create VueI18n instance with options
const i18n = new VueI18n({
    locale: 'en', // set locale
    messages: translations, // set locale translations
})

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = document.getElementById("app");

new Vue({
    vuetify: new Vuetify(),
    i18n,
    render: h =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: name => require(`./Pages/${name}`).default
            }
        }),
    methods: {
        confirmDestroy: (title, message, callback) => {
            Swal.fire({
                icon: "warning",
                title: title,
                html: `${message}`,
                showConfirmButton: true,
                confirmButtonText: 'Yes, remove',
                confirmButtonColor: '#EC0000',
                showCancelButton: true,
                cancelButtonText: "Cancel"
            }).then(result => {
                if (result.isConfirmed) {
                    // Action
                    callback();
                }
            });
        }
    }
}).$mount(app);

// const app = new Vue({
//     el: '#app',
// });
