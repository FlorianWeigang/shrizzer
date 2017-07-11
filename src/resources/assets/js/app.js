import KeenUI from 'keen-ui';

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
window.eventHub = new Vue();

window.vueSharedState = {};


Vue.component('medialist', require('./components/MediaList.vue'));
Vue.component('mediaitem', require('./components/MediaItem.vue'));
Vue.component('flasher', require('./components/Flasher.vue'));
Vue.component('subscriberbox', require('./components/SubscriberBox.vue'));
Vue.component('mediaform', require('./components/MediaForm.vue'));
Vue.component('sh-sidebar', require('./components/Sidebar.vue'));
Vue.component('sh-settings', require('./components/Settings.vue'));
Vue.component('sh-modalbar', require('./components/ModalBar.vue'));
Vue.component('activitylog', require('./components/AvtivityLog.vue'));
Vue.component('notification', require('./components/Notification.vue'));

Vue.use(KeenUI);

const app = new Vue({
    el: '#app',
    data: {
        sharedState: vueSharedState,
        showFlasher: false,
        showShareLink: false,
        showPreLoader: false,
        page: 'timeline',
        isSubscribed: false,
        lastVisited: false
    },
    created: function () {
        eventHub.$on('start-loading-links', this.showLoader);
        eventHub.$on('finished-loading-links', this.hideLoader);
    },
    methods: {
        openModal: function (ref) {
            window.eventHub.$emit('open-modal', ref);
        },
        showLoader: function () {
            this.showPreLoader = true;
        },
        hideLoader: function () {
            this.showPreLoader = false;
        },
        switchPage: function (event, page) {
            if (event) {
                event.preventDefault();
            }

            if (page == 'activity') {
                window.eventHub.$emit('activity-load-content');
            }

            this.page = page;

            //setTimeout(function() {
            //    var navMain = $("#menu");
            //    navMain.collapse('hide');
            //}, 100)
        },
        toggleMenu: function(event, menu) {
            if (event) {
                event.preventDefault();
            }

            this[menu] = !this[menu];
        }
    }
});
