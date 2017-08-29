require('./bootstrap');

window.Vue = require('vue');

import VueClipboard from 'vue-clipboard2'

import Buefy from 'buefy';
import Vue2Filters from 'vue2-filters'

import ConfirmItem from './components/ConfirmItem.vue';

import TaskListItem from './components/TaskListItem.vue';
import StudioListItem from './components/StudioListItem.vue';
import TaskStatusesItem from './components/TaskStatusesItem.vue';

import ManageTasksItem from './components/ManageTasksItem.vue';
import ManageStatusesItem from './components/ManageStatusesItem.vue';
import ManageTasksChildrenItem from './components/ManageTasksChildrenItem.vue';



Vue.use(Vue2Filters);
Vue.use(Buefy, {
    defaultIconPack: 'fa'
});

Vue.use(VueClipboard);

Vue.component('confirm-item', ConfirmItem);

Vue.component('task-list-item', TaskListItem);
Vue.component('studio-list-item', StudioListItem);
Vue.component('task-statuses-item', TaskStatusesItem);

Vue.component('manage-statuses-item', ManageStatusesItem);
Vue.component('manage-tasks-item', ManageTasksItem);
Vue.component('manage-tasks-children-item', ManageTasksChildrenItem);

//-- Filter
Vue.filter('truncate', function (text, length, clamp) {
    clamp = clamp || '...';
    length = length || 30;

    if (text.length <= length) return text;

    var tcText = text.slice(0, length - clamp.length);
    var last = tcText.length - 1;


    while (last > 0 && tcText[last] !== ' ' && tcText[last] !== clamp[0]) last -= 1;

    // Fix for case when text dont have any `space`
    last = last || length - clamp.length;

    tcText =  tcText.slice(0, last);
    return tcText + clamp;
});

Vue.filter('toFixed', function (text, digits) {
    return parseFloat(text).toFixed(digits);
});

new Vue({ el: '#app', data: { navigation: {}, activeTab: null, } });
