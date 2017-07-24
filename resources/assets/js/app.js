
require('./bootstrap');

window.Vue = require('vue');

import Buefy from 'buefy';
import Vue2Filters from 'vue2-filters'

import TaskListItem from './components/TaskListItem.vue';
import ManageTasksItem from './components/ManageTasksItem.vue';
import ManageStatusesItem from './components/ManageStatusesItem.vue';

Vue.use(Vue2Filters)
Vue.use(Buefy, {
    defaultIconPack: 'fa'
});

Vue.component('task-list-item', TaskListItem);
Vue.component('manage-tasks-item', ManageTasksItem);
Vue.component('manage-statuses-item', ManageStatusesItem);

new Vue({ el: '#app', data: { navigation: {} } });
