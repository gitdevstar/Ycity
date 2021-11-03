/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;
import vueDebounce from 'vue-debounce'

import 'jquery-ui/ui/widgets/sortable.js';

Vue.use(vueDebounce);

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/UpdateActiveClientComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('updateactiveclient-component', require('./components/UpdateActiveClientComponent.vue').default);
Vue.component('register-component', require('./components/RegisterComponent.vue').default);
Vue.component('login-component', require('./components/LoginComponent.vue').default);
Vue.component('edituser-component', require('./components/EditUserComponent.vue').default);
Vue.component('client-component', require('./components/ClientComponent.vue').default);
Vue.component('job-component', require('./components/JobComponent.vue').default);
Vue.component('creator-component', require('./components/CreatorComponent.vue').default);
Vue.component('notifications-component', require('./components/NotificationComponent.vue').default);
Vue.component('chat-messages', require('./components/ChatMessages.vue').default);
Vue.component('chat-form', require('./components/ChatForm.vue').default);

const VueUploadComponent = require("vue-upload-component");
Vue.component('chat-upload', VueUploadComponent);
Vue.component('final-draft', VueUploadComponent);

Vue.component('final-draft-component', require('./components/FinalDraftComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

if(document.getElementById("notifications")){
    const notifications = new Vue({
        el: '#notifications',
    });
}

if(document.getElementById("app")){
    const app = new Vue({
        el: '#app',
    });
}
if(document.getElementById("app2")){
    const app2 = new Vue({
        el: '#app2',
    });
}
if(document.getElementById("app3")){
    const app3 = new Vue({
        el: '#app3',
    });
}

if(document.getElementById("finalDraftContainer")){
    const finalDraftContainer = new Vue({
        el: '#finalDraftContainer',
    });
}

if(document.getElementById("chat")) {
    const chat = new Vue({
        el: '#chat',

        data: {
            messages: [],
        },
        created() {
            var job = $('meta[name="job-id"]').attr('content');
            this.fetchMessages(job);
        },
        methods: {
            fetchMessages(job) {
                axios.post('/getMessages', {"jobId": job}).then(response => {
                    this.messages = response.data;
                });
            },

            addMessage(message) {
                this.messages.push(message);
                //console.log(message);

                axios.post('/messages', message).then(response => {
                    setTimeout(function(){
                        $("#chatContainer").animate({ scrollTop: $('#chatContainer').prop("scrollHeight")}, 750);
                    },500);
                });
            }
        }
    });
}


