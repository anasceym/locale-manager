
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

Vue.component('example', require('./components/Example.vue'));
Vue.component('project', require('./components/projects/Projects.vue'));
Vue.component('projectnew', require('./components/projects/New.vue'));
Vue.component('projectupdate', require('./components/projects/Update.vue'));
Vue.component('projectlanguage', require('./components/project-language/List.vue'));
Vue.component('projectnamespace', require('./components/project-namespace/List.vue'));
Vue.component('projectnamespacedetails', require('./components/project-namespace/Show.vue'));
Vue.component('passport-clients', require('./components/passport/Clients.vue'));
Vue.component('passport-authorized-clients', require('./components/passport/AuthorizedClients.vue'));
Vue.component('passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue'));

const app = new Vue({
    el: '#app',

    mounted() {

        $('[data-plugin=selectpicker]').selectpicker({
            liveSearch: true
        })
    }
});
