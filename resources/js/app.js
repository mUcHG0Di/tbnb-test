/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// import Vue from "vue";
import Vuetify from "vuetify";
import { InertiaApp } from "@inertiajs/inertia-vue";
import Swal from "sweetalert2";

import "vuetify/dist/vuetify.min.css";

window.Vue = require('vue').default;

Vue.use(InertiaApp);
Vue.use(Vuetify);

require("./filters");

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const Toast = Swal.mixin({
    toast: true,
    position: "bottom",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: toast => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    }
});
window.Toast = Toast;

const app = document.getElementById("app");

new Vue({
    vuetify: new Vuetify(),
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
