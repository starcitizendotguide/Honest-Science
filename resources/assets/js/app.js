
require('./bootstrap');

window.Vue = require('vue');

import Buefy from 'buefy';
import Vue2Filters from 'vue2-filters'

import TaskListItem from './components/TaskListItem.vue';
import ManageTasksItem from './components/ManageTasksItem.vue';

Vue.use(Vue2Filters)
Vue.use(Buefy, {
    defaultIconPack: 'fa'
});

Vue.component('task-list-item', TaskListItem);
Vue.component('manage-tasks-item', ManageTasksItem);

new Vue({ el: '#app', data: { navigation: {} } });
