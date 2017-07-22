
require('./bootstrap');

window.Vue = require('vue');

import Buefy from 'buefy';
import Vue2Filters from 'vue2-filters'

import TaskListItem from './components/TaskListItem.vue';

Vue.use(Vue2Filters)
Vue.use(Buefy);

Vue.component('task-list-item', TaskListItem);

new Vue({
  el: '#app'
});
