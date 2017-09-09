<template>
    <div class="columns">
        <div class="column is-offset-1 is-7">
            <div class="task-list">
                <div class="field is-grouped">
                    <p class="control">
                        <input v-model="meta.search" class="input highlighted-element highlighted-text" placeholder="Search...">
                    </p>
                    <div class="field has-addons control">
                        <p class="control" v-for="status in meta.statuses">
                            <a v-on:click="statusOnClick(status.id)" class="button highlighted-element highlighted-text" v-bind:class="status.css.button_classes" >
                               <span class="icon is-small"><i :class="status.css.icon"></i></span>
                               <span>{{ status.name }}</span>
                           </a>
                        </p>
                    </div>
                </div>

                <div class="field is-grouped">
                    <div class="field has-addons control">
                        <p class="control" v-for="type in meta.types">
                            <a v-on:click="typeOnClick(type.id)" class="button highlighted-element highlighted-text" v-bind:class="type.css.button_classes" >
                               <span class="icon is-small"><i :class="type.css.icon"></i></span>
                               <span>{{ type.name }}</span>
                           </a>
                        </p>
                    </div>

                    <p class="control is-pulled-right">
                        <a v-on:click="resetMeta" class="button highlighted-element highlighted-text">
                            <span class="icon is-small"><i class="fa fa-undo"></i></span>
                            <span>Reset</span>
                        </a>
                    </p>
                </div>

                <div :id="task.id" @click="triggerCollapse($event, task)" class="box task-box highlighted-element" :class="{'is-active': task.collapsed}" v-for="task in filteredItems">
                    <article class="media media-fix">
                        <div class="media-content">
                            <div class="content">
                                <div class="m-b-10">
                                    <strong>{{ task.name }}</strong>

                                    <span>
                                        <i class="fa is-focused is-pulled-right" v-bind:class="{ 'fa-arrow-right': !task.collapsed, 'fa-arrow-down': task.collapsed }"></i>
                                    </span>

                                    <p v-if="!task.collapsed">{{ task.description | truncate(120) }}</p>
                                    <transition name="fade">
                                        <p v-if="task.collapsed">{{ task.description }}</p>
                                    </transition>

                                    <progress class="progress" :value="task.progress" max="1">{{ toFixed(task.progress * 100, 2) }}%</progress>

                                    <b-tooltip :label="task.status.name + ' - ' + toFixed(task.progress * 100, 2) + '%'" type="is-dark" square dashed animated>
                                        <span class="icon is-small is-muted"><i :class="task.status.css_icon"></i></span>
                                    </b-tooltip>
                                    <b-tooltip v-if="task.standalone" :label="task.type.name" type="is-dark" square dashed animated>
                                        <span class="icon is-small is-muted"><i :class="task.type.css_icon"></i></span>
                                    </b-tooltip>

                                    <span class="is-pulled-right is-muted is-muted">Last Updated: {{ task.updated_at_diff }}</span>

                                </div>
                                <transition name="fade">
                                    <div v-if="task.collapsed" class="ignore-click">
                                        <div class="box highlighted-element" v-for="child in task.children">
                                            <article class="media media-fix">
                                                <div class="media-content">
                                                    <div class="content">
                                                        <strong class="">{{ child.name }}</strong>
                                                        <br />
                                                        {{ child.description }}
                                                        <br />

                                                        <b-tooltip :label="child.status.name + ' - ' + toFixed(child.progress * 100, 2) + '%'" type="is-dark" square dashed animated>
                                                            <span class="icon is-small is-muted"><i :class="child.status.css_icon"></i></span>
                                                        </b-tooltip>
                                                        <b-tooltip :label="child.type.name" type="is-dark" square dashed animated>
                                                            <span class="icon is-small is-muted"><i :class="child.type.css_icon"></i></span>
                                                        </b-tooltip>

                                                        <span class="is-pulled-right is-muted">Last Updated: {{ task.updated_at_diff }}</span>

                                                    </div>
                                                </div>
                                            </article>
                                        </div>

                                    </div>
                                </transition>
                            </div>
                        </div>
                    </article>
                </div>

                <div v-if="!loading.disabled" class="has-text-centered">
                    <div v-if="loading.available">
                        <a class="button is-fullwidth highlighted-element highlighted-text" @click="loadTasksPaginated()">Expand the universe.</a>
                    </div>
                    <div v-if="!loading.available">
                         <span>You have reached the end of the universe.</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- Interaction Bar -->
        <div v-if="" class="interaction-bar container">
                <div class="bar-content">

                    <div class="column is-3 m-t-100">
                        <div class="card card-content highlighted-element" :class="{'is-active': (meta.interactionBar.task !== null)}">

                            <div v-if="!meta.interactionBar.pages.isDefault">

                                <p class="has-text-centered m-b-10"><b>{{ meta.interactionBar.pageTitle }}</b></p>

                                <p v-if="!meta.interactionBar.pages.isOverview" class="m-b-10">
                                    <span class="icon is-small" @click="interactionBarSwitchPage('isOverview')"><i class="fa fa-chevron-left"></i></span>
                                    Go Back
                                </p>
                            </div>

                            <div v-if="meta.interactionBar.pages.isDefault">
                                <p>This is our interactive bar. You can open any task to test its behaviour.</p>
                            </div>

                            <div v-if="meta.interactionBar.pages.isOverview">
                                <a class="button highlighted-element highlighted-text is-fullwidth m-b-5" @click="interactionBarComments">Comment</a>
                                <a class="button highlighted-element highlighted-text is-fullwidth m-b-5">Share</a>
                                <a class="button highlighted-element highlighted-text is-fullwidth m-b-5" @click="interactionBarSources">Sources</a>
                            </div>

                            <div v-if="meta.interactionBar.pages.isComment">
                                <div class="disquscard-content" id="disqus_thread"></div>
                            </div>

                            <div v-if="meta.interactionBar.pages.isSources" class="has-text-centered">
                                <ul>
                                    <confirm-item
                                            v-for="(source, index) in meta.interactionBar.task.sources"
                                            :key="source.id"

                                            title="You are leaving Star Citizen - Honest Science."
                                            :message="source.link + ' is not an official Honest Science site.'"
                                            positive="Continue to external site."
                                            negative="Cancel"
                                            :url="source.link"
                                            theme="is-danger"
                                            width=960>
                                        <li>{{ source.link | truncate(50)  }}</li>
                                    </confirm-item>
                                </ul>
                                <span v-if="meta.interactionBar.task.sources.length == 0">
                        No sources available.
                    </span>
                            </div>

                        </div>
                    </div>



                </div>
        </div>

        <!-- Interaction Bar End -->
    </div>
</template>

<script type="text/javascript">
export default {
    data: function() {
        return {
            tasks: [],
            container: {
                all: [],
                paginated: [],
            },
            loading: {
                available: true,
                disabled: false,
                page: 0,
                size: 5,
            },
            meta: {
                search: '',
                interactionBar: {
                    task: null,
                    pageTitle: null,
                    pages: {
                        isDefault: false,
                        isOverview: false,
                        isComment: false,
                        isSources: false,
                    },
                },
                statuses: [],
                types: [],
            },
        };
    },
    methods: {
        //--- Resets all the meta values. They are mainly used for the search function &
        //    the interaction bar.
        resetMeta: function() {

            //--- Reset Collapse
            this.tasks.map(v => { v.collapsed = false;  });

            //--- Reset search value
            this.meta.search = '';

            //--- Reset statuses & types
            for(var i = 0; i < this.meta.statuses.length; i++) {
                this.meta.statuses[i].css.button_classes['is-active'] = false;
            }
            for(var i = 0; i < this.meta.types.length; i++) {
                this.meta.types[i].css.button_classes['is-active'] = false;
            }

            //--- Reset Interaction Bar
            this.resetInteractionBar();
        },
        //--- Called when the user clicks on the "status" button to enable or disable
        //    it.
        statusOnClick(id) {
            this.meta.statuses[id].css.button_classes['is-active'] = !this.meta.statuses[id].css.button_classes['is-active'];
        },
        //--- Called when the user clicks on the "type" button to enable or disable
        //    it.
        typeOnClick(id) {
            this.meta.types[id].css.button_classes['is-active'] = !this.meta.types[id].css.button_classes['is-active'];
        },
        //--- Called when the user clicks on the arrow next to a task to collapse it.
        //    It collapse all other tasks and resets or loads the interaction bar.
        triggerCollapse(evt, task) {

            var isIgnoringTriggerEvent = function(element) {
                if(typeof element === 'undefined' || element === null) {
                    return false;
                } else if(element.classList.contains('ignore-click')) {
                     return true;
                 }

                return isIgnoringTriggerEvent(element.parentElement);
            };

            if(isIgnoringTriggerEvent(evt.target) === true) {
                return;
            }


            //--- Reset Collapse & Reset Interaction Bar
            this.tasks.map(v => { if(v != task) v.collapsed = false;  });

            //--- Collapse or uncollapse children
            task.collapsed = !task.collapsed;

            //--- Trigger
            if(task.collapsed === true) {
                this.meta.interactionBar.task = task;
                this.interactionBarSwitchPage('isOverview');
            } else {
                this.resetInteractionBar();
            }

        },
        //--- Resets the interaction bar to its default status.
        resetInteractionBar() {
            this.interactionBarSwitchPage('isDefault');
            this.meta.interactionBar.task = null;
            this.meta.interactionBar.content = null;
        },
        //--- Switch page
        interactionBarSwitchPage(name) {
            for (var page in this.meta.interactionBar.pages) {
                // skip loop if the property is from prototype
                if(!this.meta.interactionBar.pages.hasOwnProperty(page)) continue;

                this.meta.interactionBar.pages[page] = (page == name);
            }

            this.meta.interactionBar.pageTitle = name.substr(2);
        },
        //--- Loads the Disqus section for an task.
        interactionBarComments() {

            var task = this.meta.interactionBar.task;

            if(task === null || task === undefined) {
                return;
            }

            this.interactionBarSwitchPage('isComment');

            var CONF_URL            = (location.protocol + '//' + window.location.hostname),
                CONF_SHORTNAME      = 'star-citizen-honest-tracker',
                CONF_IDENTIFIER     = task.id,
                CONF_TITLE          = (task.name + ' - Discussion');

            var id = CONF_IDENTIFIER,
            disqus_shortname = CONF_SHORTNAME,
            disqus_identifier = id,
            disqus_title = CONF_TITLE,
            disqus_url = (CONF_URL + "/#!" + id);

            if(typeof DISQUS === 'undefined') {
                (function() {  // DON'T EDIT BELOW THIS LINE
                    var d = document, s = d.createElement('script');

                    s.src = 'https://star-citizen-honest-tracker.disqus.com/embed.js';
                    s.setAttribute('data-timestamp', + new Date());
                    (d.head || d.body).appendChild(s);

                })();
            } else {
                DISQUS.reset({
                    reload: true,
                    config: function () {
                        this.page.identifier = id;
                        this.page.url = disqus_url;
                    }
                });
            }
         },
        interactionBarSources() {
            this.interactionBarSwitchPage('isSources');
        },
        //---
        loadTasksPaginated() {

            this.loading.page++;

            //--- Note: Using this instead of Array.slice() because Vue doesn't detect the changes
            // for some reason, even if I used the specified ways to trigger it.
            var start   = (this.loading.page - 1 ) * this.loading.size;
            var end     = Math.min((start + this.loading.size), this.container.all.length);
            for(var i = start; i < end; i++) {
                this.container.paginated.push(this.container.all[i]);
            }

            if(this.container.paginated.length >= this.container.all.length) {
                this.loading.available = false;
            }

            this.tasks = Object.assign([], this.tasks, this.container.paginated);

        },
        //--- Help Function: formations a number using fixed-point notation.
        toFixed(value, digits) {
            return value.toFixed(digits);
        },
        //--- Help Function: checks if the task has a child that has a 'TaskType'
        //    the user is currently looking for
        hasChildTypesActive(task) {

            for(var key in task.children) {
                for(var i = 0; i < this.meta.types.length; i++) {
                    if(this.meta.types[i].id === task.children[key].type.id && this.meta.types[i].css.button_classes['is-active'] === true) {
                        return true;
                    }
                }
            }

            return false;

        },
        hasTaskTypesActive(task) {

            if(task.type === null) {
                return false;
            }

            for(var i = 0; i < this.meta.types.length; i++) {
                if(this.meta.types[i].id === task.type.id && this.meta.types[i].css.button_classes['is-active'] === true) {
                    return true;
                }
            }

            return false;

        }
    },
    computed: {
        filteredItems: function () {
            var _tmp        = this.container.all,
                self        = this,
                search      = this.meta.search,
                statuses    = this.meta.statuses,
                types       = this.meta.types;


            //--- Going through all "status buttons" to decide whether or not, we
            // have to include this in our search.
            var statusMode = false;
            for (var key in statuses) {
                if(statuses[key].css.button_classes['is-active'] === true) {
                    statusMode = true;
                    break;
                }
            }

            var typeMode = false;
            for (var key in types) {
                if(types[key].css.button_classes['is-active'] === true) {
                    typeMode = true;
                    break;
                }
            }

            if(!search && !statusMode && !typeMode){
                this.loading.disabled = false;
                return this.container.paginated;
            }

            this.loading.disabled = true;
            search = search.trim().toLowerCase();

            _tmp = _tmp.filter(function(item){

                //@HACK: Why do we have strings as keys? The Rest API is passing ints
                //       and casting it doesn't help for some reason, weird...
                if(
                    (statuses[String(item.status.id)].css.button_classes['is-active'] === true || !statusMode) &&
                    (self.hasChildTypesActive(item) || self.hasTaskTypesActive(item) || !typeMode) &&
                    (
                        item.name.toLowerCase().indexOf(search) !== -1 ||
                        item.description.toLowerCase().indexOf(search) !== -1
                    )
                ){
                    return item;
                }
            });

            return _tmp;
        },
    },
    mounted: function() {
        axios.get(route('statuses.index'))
            .then(response => {

                var data = response.data;
                var $this = this;
                data.forEach(function(e) {

                    //--- Add to buttons
                    var tmp = {
                        id: e.id,
                        name: e.name,
                        css: {
                            button_classes: {
                                'is-active': false,
                            },
                            icon: e.css_icon
                        },
                        created_at: e.created_at,
                        updated_at: e.updated_at
                    };
                    tmp.css.button_classes[e.css_class] = true;
                    $this.meta.statuses.push(tmp);

                });

            });

        axios.get(route('types.index'))
            .then(response => {
                var data = response.data;
                var $this = this;
                data.forEach(function(e) {

                    //--- Add to buttons
                    var tmp = {
                        id: e.id,
                        name: e.name,
                        css: {
                            button_classes: {
                                'is-active': false,
                            },
                            icon: e.css_icon
                        },
                        created_at: e.created_at,
                        updated_at: e.updated_at
                    };
                    tmp.css.button_classes[e.css_class] = true;
                    $this.meta.types.push(tmp);

                });
            });

        axios.get(route('tasks.index'))
            .then(response => {

                var data = response.data;

                data.forEach(function(e) {
                    e.collapsed = false;
                });

                this.container.all = data;
                //--- Load initial
                this.loadTasksPaginated();
                this.interactionBarSwitchPage('isDefault');
            });

    },
}
</script>
