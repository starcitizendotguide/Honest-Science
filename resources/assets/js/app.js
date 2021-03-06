require('./bootstrap');

window.Vue = require('vue');

import Buefy from 'buefy';
import Vue2Filters from 'vue2-filters';
import VueClipboard from 'vue-clipboard2';

import ConfirmItem from './components/ConfirmItem.vue';
import FlashItem from './components/FlashItem.vue';
import TaskPreviewItem from './components/TaskPreviewItem.vue';
import LogItem from './components/LogItem.vue';
import ApplyItem from './components/ApplyItem.vue';

import TaskListItem from './components/TaskListItem.vue';
import StudioListItem from './components/StudioListItem.vue';
import TaskStatusesItem from './components/TaskStatusesItem.vue';

import ManageStatusesItem from './components/ManageStatusesItem.vue';
import ManageUserItem from './components/ManageUserItem.vue';

import ManageTasksItem from './components/ManageTasksItem.vue';
import ManageTasksChildrenItem from './components/ManageTasksChildrenItem.vue';

import ManageDeprecatedQueueItem from './components/ManageDeprecatedQueueItem.vue';
import ManageVerifyQueueItem from './components/ManageVerifyQueueItem.vue';
import ManageSourcesItem from './components/ManageSourcesItem.vue';
import ManageGroupsItem from './components/ManageGroupsItem.vue';

Vue.use(Vue2Filters);
Vue.use(Buefy, {
    defaultIconPack: 'fa'
});

Vue.use(VueClipboard);

Vue.component('confirm-item', ConfirmItem);
Vue.component('flash-item', FlashItem);
Vue.component('task-preview-item', TaskPreviewItem);
Vue.component('log-item', LogItem);
Vue.component('apply-item', ApplyItem);

Vue.component('task-list-item', TaskListItem);
Vue.component('studio-list-item', StudioListItem);
Vue.component('task-statuses-item', TaskStatusesItem);

Vue.component('manage-statuses-item', ManageStatusesItem);
Vue.component('manage-user-item', ManageUserItem);

Vue.component('manage-tasks-item', ManageTasksItem);
Vue.component('manage-tasks-children-item', ManageTasksChildrenItem);

Vue.component('manage-deprecated-queue-item', ManageDeprecatedQueueItem);
Vue.component('manage-verify-queue-item', ManageVerifyQueueItem);
Vue.component('manage-sources-item', ManageSourcesItem);
Vue.component('manage-groups-item', ManageGroupsItem);

//-- Filter
Vue.filter('truncate', function (text, length, clamp) {
    clamp = clamp || '...';
    length = length || 30;

    if (text.length <= length) return text;

    var tcText = text.slice(0, length - clamp.length);
    var last = tcText.length - 1;


    while (last > 0 && tcText[last] !== ' ' && tcText[last] !== clamp[0]) last -= 1;

    last = last || length - clamp.length;

    tcText =  tcText.slice(0, last);
    return tcText + clamp;
});

Vue.filter('toFixed', function (text, digits) {
    return parseFloat(text).toFixed(digits);
});

new Vue({ el: '#app', data: { activeTab: null, navigation: {} } });
