
require('./bootstrap');

window.Vue = require('vue');

import Buefy from 'buefy';
import Vue2Filters from 'vue2-filters'

import Example from './components/Example.vue';

Vue.use(Vue2Filters)
Vue.use(Buefy);

Vue.component('example-item', Example);

new Vue({
  el: '#app'
});
