<template xmlns:v-clipboard="http://www.w3.org/1999/xhtml">
    <div class="columns">
        <div class="column is-offset-1 is-7">

            <div class="field is-grouped">
                <div class="field has-addons control">
                    <p class="control">
                        <b-tooltip
                                :label="meta.search.error.length > 0 ? meta.search.error : meta.search.suggestion"
                                :always="meta.search.error.length > 0 || meta.search.suggestion.length > 0"
                                multilined
                                type="is-info">
                            <input v-model="meta.search.query" class="input highlighted-element highlighted-text" placeholder="Search..." :disabled="meta.shared.active">
                        </b-tooltip>
                    </p>
                    <p class="control">
                        <a @click="openSearchHelp" class="button highlighted-element highlighted-text" :disabled="meta.shared.active">
                            <span class="icon is-right"><i class="fa fa-question"></i></span>
                        </a>
                    </p>
                    <p v-on:click="resetMeta" class="control">
                        <b-tooltip
                                label="Click me to see all tasks!"
                                :always="meta.shared.active"
                                :active="meta.shared.active"
                                multilined
                                type="is-info">
                            <a class="button highlighted-element highlighted-text" :class="{'is-active': meta.shared.active }">
                                <span class="icon is-small"><i class="fa fa-undo"></i></span>
                                <span>Reset</span>
                            </a>
                        </b-tooltip>
                    </p>
                </div>
            </div>
            <div class="field is-grouped is-hidden-touch">
                <div class="field has-addons control">
                    <p class="control" v-for="status in meta.statuses">
                        <a v-on:click="statusOnClick(status.id)" class="button highlighted-element highlighted-text" v-bind:class="status.css.button_classes" :disabled="meta.shared.active">
                            <span class="icon is-small"><i :class="status.css.icon"></i></span>
                            <span>{{ status.name }}</span>
                        </a>
                    </p>
                </div>
            </div>
            <div class="field is-grouped is-hidden-touch">
                <div class="field has-addons control">
                    <p class="control" v-for="type in meta.types">
                        <a v-on:click="typeOnClick(type.id)" class="button highlighted-element highlighted-text" v-bind:class="type.css.button_classes" :disabled="meta.shared.active">
                            <span class="icon is-small"><i :class="type.css.icon"></i></span>
                            <span>{{ type.name }}</span>
                        </a>
                    </p>
                </div>

                <!--<div class="field has-addons control">
                    <p class="control">
                        <a v-on:click="releaseTimeOnClick(true, null)" href="#" class="button highlighted-element highlighted-text" v-bind:class="{'is-active': meta.timeOfRelease.preAtLaunch}" :disabled="meta.shared.active">
                            <span class="icon is-small"><i class="fa"></i></span>
                            <span>Pre-/At-Launch</span>
                        </a>
                    </p>

                    <p class="control">
                        <a v-on:click="releaseTimeOnClick(null, true)" href="#" class="button highlighted-element highlighted-text" v-bind:class="{'is-active': meta.timeOfRelease.postLaunch}" :disabled="meta.shared.active">
                            <span class="icon is-small"><i class="fa"></i></span>
                            <span>Post-Launch</span>
                        </a>
                    </p>
                </div>-->
            </div>

            <div class="task-list">

                <div :id="'task-' + task.id" @click="triggerTaskCollapse($event, task)" class="box task-box highlighted-element" :class="{'is-active': task.collapsed}" v-for="task in filteredItems">
                    <article class="media media-fix">
                        <div class="media-content">
                            <div class="content">
                                <div class="m-b-10">
                                    <strong>{{ task.name }}</strong>

                                    <p class="description-color" v-if="!task.collapsed && task.description.length > 120">{{ task.description | truncate(120) }}</p>
                                    <transition name="fade">
                                        <p class="description-color" v-if="task.collapsed || task.description.length <= 120">{{ task.description }}</p>
                                    </transition>

                                    <div class="progressbar">
                                        <div :style="progressBarStyle(task)"></div>
                                    </div>

                                </div>
                                <div class="m-t-10 meta-color" :class="{ 'm-b-10': task.collapsed && task.standalone == false }">
                                    <span class="m-r-1 ignore-click">Status: </span>
                                    <b-tooltip :label="task.status.name + ' - ' + toFixed(task.progress * 100, 2) + '%'" type="is-dark" square animated>
                                        <i :class="task.status.css_icon" class="icon is-icon-size ignore-click"></i>
                                    </b-tooltip>

                                    <span v-if="task.standalone" class="m-l-2 m-r-1 ignore-click">Type:</span>
                                    <b-tooltip v-if="task.standalone" v-for="type in task.types" :key="type.id" :label="type.name" type="is-dark" square animated>
                                        <i :class="type.css_icon" class="icon is-icon-size ignore-click"></i>
                                    </b-tooltip>

                                    <span class="is-pulled-right ignore-click">Last Updated: {{ task.updated_at_diff }}</span>
                                </div>

                                <transition name="fade">
                                    <div v-if="task.collapsed" class="ignore-click">
                                        <div :id="'task-child-' + child.id" @click="triggerChildCollapse($event, child)" class="box task-box highlighted-element" :class="{'is-active': isChildSelected(child)}" v-for="child in task.children">
                                            <article class="media media-fix">
                                                <div class="media-content">
                                                    <div class="content">
                                                        <strong>{{ child.name }}</strong>
                                                        <br />
                                                        <span class="description-color m-t-10">{{ child.description }}</span>
                                                        <br />

                                                        <div class="m-t-10 meta-color">
                                                            <span class="m-r-1 ignore-click-child">Status: </span>
                                                            <b-tooltip :label="child.status.name + ' - ' + toFixed(child.progress * 100, 2) + '%'" type="is-dark" square animated>
                                                                <i :class="child.status.css_icon" class="icon is-icon-size ignore-click-child"></i>
                                                            </b-tooltip>

                                                            <span class="m-l-2 m-r-1 ignore-click-child">Type:</span>
                                                            <b-tooltip v-for="type in child.types" :key="type.id" :label="type.name" type="is-dark" square animated>
                                                                <i :class="type.css_icon" class="icon is-icon-size ignore-click-child"></i>
                                                            </b-tooltip>

                                                            <span class="is-pulled-right">Last Updated: {{ child.updated_at_diff }}</span>
                                                        </div>
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
                    <div v-if="!loading.available && container.shared.length == 0">
                         <span>You have reached the end of the universe.</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- Interaction Bar -->
        <div class="column is-3 m-t-150">
            <div class="interaction-bar is-hidden-touch">
                <div class="bar-content is-expanded">

                    <div class="card card-content highlighted-element" :class="{'is-active': (meta.interactionBar.task !== null)}">

                        <div v-if="!meta.interactionBar.pages.isDefault">

                            <p class="has-text-centered m-b-10"><b>{{ meta.interactionBar.pageTitle }}</b></p>

                            <span @click="interactionBarSwitchPage('isOverview')" v-if="!meta.interactionBar.pages.isOverview" class="m-b-10 clickable">
                                <span class="icon is-small"><i class="fa fa-chevron-left"></i></span>
                                Go Back
                            </span>
                        </div>

                        <div v-if="meta.interactionBar.pages.isDefault">
                            <p>This is our interactive bar. You can open any task to test its behaviour.</p>
                        </div>

                        <div v-if="meta.interactionBar.pages.isOverview">
                            <a class="button highlighted-element highlighted-text is-fullwidth m-b-5" @click="interactionBarComments">Comment</a>
                            <a v-if="typeof meta.interactionBar.task.standalone == 'undefined' || meta.interactionBar.task.standalone == true" class="button highlighted-element highlighted-text is-fullwidth m-b-5" @click="interactionBarSources">
                                Sources
                            </a>
                            <a class="button highlighted-element highlighted-text is-fullwidth m-b-5" @click="interactionBarShare">Share</a>
                        </div>

                        <div v-if="meta.interactionBar.pages.isComment" v-html="a">
                        </div>

                        <div v-if="meta.interactionBar.pages.isShare">

                            <div class="field has-addons">
                                <p class="control is-expanded">
                                    <input type="text" class="input highlighted-element highlighted-text"
                                           :value="'https://starcitizentracker.info/#share-' + (typeof meta.interactionBar.task.standalone === 'undefined' ? 'c-' : 't-') + meta.interactionBar.task.id"
                                    >
                                </p>
                                <p class="control">
                                    <b-tooltip
                                            :label="'Copied to clipboard!'"
                                            :always="meta.shared.copy.active"
                                            :active="meta.shared.copy.active"
                                            animated
                                            type="is-success">
                                        <button class="button highlighted-element highlighted-text"
                                                v-clipboard:copy="'https://starcitizentracker.info/#share-' + (typeof meta.interactionBar.task.standalone === 'undefined' ? 'c-' : 't-') + meta.interactionBar.task.id"
                                                v-clipboard:success="clipboardSuccess">
                                            <span class="icon is-right"><i class="fa fa-clipboard"></i></span>
                                        </button>
                                    </b-tooltip>
                                </p>
                            </div>


                        </div>

                        <div v-if="meta.interactionBar.pages.isSources" class="has-text-centered">
                            <ul>
                                <confirm-item
                                        v-for="(source, index) in meta.interactionBar.task.sources"
                                        :key="source.id"

                                        title="You are leaving Honest Science."
                                        :message="source.link + ' is not an official Honest Science site.'"
                                        positive="Continue to external site"
                                        negative="Cancel"
                                        :url="source.link"
                                        theme="is-danger"
                                        width=960>
                                    <li>{{ source.link | truncate(75)  }}</li>
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
            a: '<div id="disqus_thread"></div>',
            tasks: [],
            container: {
                all: [],
                paginated: [],
                shared: [],
            },
            loading: {
                available: true,
                disabled: false,
                page: 0,
                size: 5,
            },
            disqus: {
                loaded: false,
            },
            meta: {
                search: {
                    query: '',
                    error: '',
                    suggestion: ''
                },
                shared: {
                    active: false,
                    copy: {
                        active: false,
                    }
                },
                timeOfRelease: {
                    preAtLaunch: false,
                    postLaunch: false,
                },
                interactionBar: {
                    padding: 0,
                    task: null,
                    pageTitle: null,
                    pages: {
                        isDefault: false,
                        isOverview: false,
                        isComment: false,
                        isShare: false,
                        isSources: false,
                    }
                },
                statuses: [],
                types: [],
            },
        };
    },
    methods: {
        clipboardSuccess() {
            this.meta.shared.copy.active = true;

            var self = this;
            setTimeout(function() {
                self.meta.shared.copy.active = false;
            }, 650);
        },
        //--- Checks whether or not the user is accessing the page
        // via a share link.
        checkForShare() {

            var value = window.location.hash.toString();

            if(value.startsWith('#share')) {

                var parts = value.split('-');

                if(parts.length === 3) {
                    var id = parseInt(parts[2]);
                    switch(parts[1].toLocaleLowerCase()) {

                        case 't': {

                            var task = null;

                            for(var t in this.container.all) {
                                if(this.container.all[t].id === id) {
                                    task = this.container.all[t];
                                    break;
                                }
                            }

                            if(!(task === null)) {
                                this.triggerTaskCollapse(null, task);

                                this.container.shared = [ task ];
                                this.tasks = this.container.shared;

                                this.meta.shared.active = true;
                            }

                        } break;

                        case 'c': {

                            var task = null;
                            var child = null;

                            for(var t in this.container.all) {
                                for(var c in this.container.all[t].children) {
                                    if(this.container.all[t].children[c].id === id) {
                                        task = this.container.all[t];
                                        child = this.container.all[t].children[c];
                                        break;
                                    }
                                }
                            }

                            if(!(task === null)) {
                                this.triggerTaskCollapse(null, task);
                                this.triggerChildCollapse(null, child);

                                this.container.shared = [ task ];
                                this.tasks = this.container.shared;

                                this.meta.shared.active = true;
                            }
                        } break;

                    }
                }

            }

        },
        //--- Opens the help for the search bar
        openSearchHelp() {

            //--- If the user is currently in shared mode he cannot
            // interact with anything search related
            if(this.meta.shared.active === true) {
                return;
            }

            this.$buefy.dialog.confirm({
                canCancel: false,
                message: "<div class=\"content\">" +
                "<h5>Search</h5>" +
                "<p>The search bar allows you to search all tasks and their children. You can also " +
                "utilize commands to query certain properties.</p>" +
                "<ul>" +
                    "<li><code>:task [id]</code> - Find a specific task by its id.</li>" +
                        "<ul><li><code>[id]</code> integer</li></ul>" +
                    "<li><code>:child [id]</code> - Find a specific child by its id.</li>" +
                        "<ul><li><code>[id]</code> integer</li></ul>" +
                    "<li><code>:progress [operator] [value]</code> - Filter tasks by their progress value.</li>" +
                        "<ul>" +
                            "<li><code>[operator]</code> <b>=</b>, <b>!=</b>, <b>></b>, <b><</b>, <b>>=</b>, <b><=</b></li>" +
                            "<li><code>[value]</code> float [0..100]</li>" +
                        "</ul>" +
                    "<li><code>:sort [field] [mode?]</code></li>" +
                        "<ul>" +
                            "<li><code>[field]</code> <b>id</b>, <b>progress</b>, <b>children</b>, <b>created</b>, <b>updated</b></li>" +
                            "<li><code>[mode?]</code> <b>asc</b> (ascending, <i>default</i>) or <b>desc</b> (descending)</li>" +
                        "</ul>" +
                "</ul>" +
                "<div>"
            });

        },
        isChildSelected(child) {
            var _t = this.meta.interactionBar.task;
            return (
                !(_t === null) &&
                (_t.standalone === undefined) &&
                _t.id === child.id
            );
        },
        progressBarStyle: function(task) {

            const interpolate = function (p, colors) {

                const _w = (1 / colors.length);
                const x_1 = Math.min(Math.floor(p / _w), colors.length - 2);
                const x_2 = x_1 + 1;

                const r_y_1 = colors[x_1][0];
                const r_y_2 = colors[x_2][0];

                const g_y_1 = colors[x_1][1];
                const g_y_2 = colors[x_2][1];

                const b_y_1 = colors[x_1][2];
                const b_y_2 = colors[x_2][2];

                let p1 = (p % _w) / _w;

                const x = [
                    ((r_y_2 * p1) + (r_y_1 * (1 - p1))),
                    ((g_y_2 * p1) + (g_y_1 * (1 - p1))),
                    ((b_y_2 * p1) + (b_y_1 * (1 - p1))),
                ];

                return x;
            };

            const colors = [
                [230, 124, 115],
                [243, 169, 109],
                [255, 214, 102],
                [171, 201, 120],
                [87, 187, 138]
            ];


            var color = interpolate(task.progress, colors);
            console.log(color);
            var hex = (
                '#' +
                ("0" + parseInt(color[0], 10).toString(16)).slice(-2) +
                ("0" + parseInt(color[1], 10).toString(16)).slice(-2) +
                ("0" + parseInt(color[2], 10).toString(16)).slice(-2)
            );

            return {
                'background-color': hex,
                'width': ((task.progress * 100) + '%')
            };

        },
        //--- Resets all the meta values. They are mainly used for the search function &
        //    the interaction bar.
        resetMeta: function() {

            //--- Reset Collapse
            this.tasks.map(v => { v.collapsed = false;  });

            //--- Reset search value
            this.meta.search.query = '';
            this.container.shared = [];
            this.meta.shared.active = false;

            //--- Reset time of release
            this.meta.timeOfRelease.preAtLaunch = false;
            this.meta.timeOfRelease.postLaunch = false;

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
            //--- If the user is in shared mode he cannot
            // interact with anything search related
            if(this.meta.shared.active === true) {
                return;
            }

            for(var i = 0; i < this.meta.statuses.length; i++) {
                if(this.meta.statuses[i].id === id) {
                    this.meta.statuses[i].css.button_classes['is-active'] = !this.meta.statuses[i].css.button_classes['is-active'];
                    break;
                }
            }

        },
        //--- Called when the user clicks on the "type" button to enable or disable
        //    it.
        typeOnClick(id) {
            //--- If the user is in shared mode he cannot
            // interact with anything search related
            if(this.meta.shared.active === true) {
                return;
            }

            for(var i = 0; i < this.meta.types.length; i++) {
                if(this.meta.types[i].id === id) {
                    this.meta.types[i].css.button_classes['is-active'] = !this.meta.types[i].css.button_classes['is-active'];
                    break;
                }
            }

        },
        //--- Called when the user clicks on the "pre-at-launch" or "post-launch" button to
        //    enable filtering of it.
        releaseTimeOnClick(preAtLaunch, postLaunch) {

            if(this.meta.shared.active === true) {
                return;
            }

            if(preAtLaunch !== null) {
                this.meta.timeOfRelease.preAtLaunch = !this.meta.timeOfRelease.preAtLaunch;
            }

            if(postLaunch !== null) {
                this.meta.timeOfRelease.postLaunch = !this.meta.timeOfRelease.postLaunch;
            }

        },
        //--- Called when the user clicks on the arrow next to a task to collapse it.
        //    It collapse all other tasks and resets or loads the interaction bar.
        triggerTaskCollapse(evt, task) {

            var current_task = this.meta.interactionBar.task;

            var isIgnoringTriggerEvent = function(element) {
                if(element === undefined || element === null) {
                    return false;
                } else if(element.classList.contains('ignore-click')) {
                     return true;
                }

                return isIgnoringTriggerEvent(element.parentElement);
            };

            if(!(evt === null) && isIgnoringTriggerEvent(evt.target) === true) {
                return;
            }

            //--- Reset Collapse & Reset Interaction Bar
            if(
                (current_task === null) ||
                !(current_task.standalone === undefined) ||
                (
                    (current_task.standalone === undefined) &&
                    (task.standalone === undefined)
                )
            ) {
                this.tasks.map(v => {
                    if (v != task) v.collapsed = false;
                });

                //--- Collapse or uncollapse children
                task.collapsed = !task.collapsed;

            }

            //--- Trigger
            if(task.collapsed === true) {
                this.meta.interactionBar.task = task;
                this.interactionBarSwitchPage('isOverview');
                //this.interactionBarMove(true);
            } else {
                this.resetInteractionBar();
            }
        },
        triggerChildCollapse(evt, child) {

            var current_task = this.meta.interactionBar.task;

            var isIgnoringTriggerEvent = function(element) {
                if(element === undefined || element === null) {
                    return false;
                } else if(element.classList.contains('ignore-click-child')) {
                    return true;
                }

                return isIgnoringTriggerEvent(element.parentElement);
            };

            if(!(evt === null) && isIgnoringTriggerEvent(evt.target) === true) {
                return;
            }

            this.meta.interactionBar.task = child;
            this.interactionBarSwitchPage('isOverview');

            //this.interactionBarMove(false);

        },
        interactionBarMove(parent) {
            var self = this;
            setTimeout(function () {
                var _element = null;

                if(parent === true) {
                    _element    = document.getElementById("task-" + self.meta.interactionBar.task.id);
                } else {
                    _element    = document.getElementById("task-child-" + self.meta.interactionBar.task.id);
                }
                var _list       = document.getElementById("task-list");
                var _reference  = document.getElementsByClassName("task-box").item(1).offsetTop;
                var _ib         = document.getElementById('interactionBar').getBoundingClientRect();

                self.meta.interactionBar.padding = _element.offsetTop - _reference + _ib.height;
            }, 300);
        },
        //--- Resets the interaction bar to its default status.
        resetInteractionBar() {
            this.interactionBarSwitchPage('isDefault');

            this.meta.interactionBar.task = null;
            this.meta.interactionBar.padding = null;
            this.meta.interactionBar.content = null;
        },
        //--- Switch page
        interactionBarSwitchPage(name) {
            for (var page in this.meta.interactionBar.pages) {
                // skip loop if the property is from prototype
                if(!this.meta.interactionBar.pages.hasOwnProperty(page)) continue;

                this.meta.interactionBar.pages[page] = (page == name);
            }

            if(this.meta.interactionBar.task === null) {
                this.meta.interactionBar.pageTitle = name.substr(2);
            } else {
                this.meta.interactionBar.pageTitle = (this.meta.interactionBar.task.name + ' - ' + name.substr(2));
            }
        },
        //--- Loads the Disqus section for an task.
        interactionBarComments() {

            // the "article"
            var task = this.meta.interactionBar.task;

            if(task === null || task === undefined) {
                return;
            }

            this.a = '<div id="disqus_thread"></div>';
            this.interactionBarSwitchPage('isComment');

            // irrelevant
            var IS_CHILD = (task.standalone === undefined);

            // Disqus Config
            var CONF_URL            = (location.protocol + '//' + window.location.hostname),
                CONF_SHORTNAME      = 'star-citizen-honest-tracker',
                CONF_IDENTIFIER     = (task.id + '-' + (IS_CHILD ? 'child' : 'parent')),
                CONF_TITLE          = (task.name + ' - Discussion');

            var disqus_shortname    = CONF_SHORTNAME;
            var disqus_identifier   = CONF_IDENTIFIER;
            var disqus_title        = CONF_TITLE;
            var disqus_url          = (CONF_URL + "/#!" + CONF_IDENTIFIER);

            // Resetting the area the comments are located in
            if(this.disqus.loaded === false) {

                var disqus_config = function () {
                    this.page.url = disqus_url;
                    this.page.identifier = disqus_identifier;

                    console.log(this.page.url);
                    console.log(this.page.identifier);
                };

                // Loading Disqus for the first time
                this.disqus.loaded = true;
                (function() {
                    var d = document, s = d.createElement('script');
                    s.src = 'https://dev-1zqni7su0b.disqus.com/embed.js';
                    s.setAttribute('data-timestamp', +new Date());
                    (d.head || d.body).appendChild(s);
                    console.log('[LOADING EMBED.JS]');
                }());

            } else {
                console.log('[ALREADY GOT EMBED.JS - TRYING TO RESET DISQUS]');
                // Trying to reset Disqus causes the error
                DISQUS.reset({
                    reload: true,
                    config: function () {
                        this.page.identifier = disqus_identifier.toString();
                        this.page.url = url;
                    }
                });
            }

        },
        interactionBarShare() {
            this.interactionBarSwitchPage('isShare');
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

            var activeTypes = [];

            for(var childKey in task.children) {
                for(var typeKey in task.children[childKey].types) {
                    activeTypes.push(parseInt(task.children[childKey].types[typeKey].id));
                }
            }
            activeTypes = activeTypes.filter(function(elem, index, self) {
                return index == self.indexOf(elem);
            });

            for(var i = 0; i < this.meta.types.length; i++) {
                if(activeTypes.includes(this.meta.types[i].id) && this.meta.types[i].css.button_classes['is-active'] === true) {
                    return true;
                }
            }

            return false;

        },
        hasTaskTypesActive(task) {

            if(task.types.length === 0) {
                return false;
            }

            var activeTypes = [];
            for(var typeKey in task.types) {
                activeTypes.push(parseInt(task.types[typeKey].id));
            }

            for(var i = 0; i < this.meta.types.length; i++) {
                if(activeTypes.includes(this.meta.types[i].id) && this.meta.types[i].css.button_classes['is-active'] === true) {
                    return true;
                }
            }

            return false;

        },
        hasChildKeyword(item) {

            var children = item.children;
            var _search = this.meta.search.query.toLowerCase();

            for(var i = 0; i < children.length; i++) {
                var child = children[i];
                if (
                    child.name.toLowerCase().indexOf(_search) !== -1 ||
                    child.description.toLowerCase().indexOf(_search) !== -1
                ) {
                    return true;
                }
            }

            return false;

        },
    },
    computed: {
        filteredItems: function () {

            let key;
            this.meta.search.error = '';
            this.meta.search.suggestion = '';

            var _tmp        = this.container.all,
                self        = this,
                search      = this.meta.search.query.toString(),
                statuses    = this.meta.statuses,
                types       = this.meta.types;


            //--- Going through all "status buttons" to decide whether or not, we
            // have to include this in our search.
            let statusMode = false;
            for (key in statuses) {
                if(statuses[key].css.button_classes['is-active'] === true) {
                    statusMode = true;
                    break;
                }
            }

            let typeMode = false;
            for (key in types) {
                if(types[key].css.button_classes['is-active'] === true) {
                    typeMode = true;
                    break;
                }
            }

            //--- No Search
            if(!search && !statusMode && !typeMode){

                if(this.meta.shared.active === true) {
                    return this.container.shared;
                }

                this.loading.disabled = false;
                return this.container.paginated;
            }

            this.loading.disabled = true;

            //--- Commands
            if(search.startsWith(':')) {


                const explode = search.split(' ');
                const command = explode[0].substr(1).toLocaleLowerCase();

                const _commands = {
                    ':task': {
                        usage: ':task [id]'
                    },
                    ':child': {
                        usage: ':child [id]'
                    },
                    ':progress': {
                        usage: ':progress [operator] [value]'
                    },
                    ':sort': {
                        usage: ':sort [field] [mode?]'
                    }
                };

                if(!search.includes(' ') || search.length < 3) {

                    const suggestions = Object.keys(_commands)
                        .filter(key => {
                            return key.indexOf(command) >= 0;
                        })
                        .reduce((res, key) => (res[key] = _commands[key], res), {});

                    const c = Object.keys(suggestions).length;
                    if(c > 0 && c <= 2) {
                        this.meta.search.suggestion = Object.values(suggestions)[0].usage ;
                    }

                } else {

                    this.meta.search.suggestion = '';

                    //--- Pre Filter
                    _tmp = _tmp.filter(function (item) {

                        //@HACK: Why do we have strings as keys? The Rest API is passing ints
                        //       and casting it doesn't help for some reason, weird...
                        if (
                            statuses.length &&
                            (statuses[item.status.id.toString()].css.button_classes['is-active'] === true || !statusMode) &&
                            (self.hasChildTypesActive(item) || self.hasTaskTypesActive(item) || !typeMode)
                        ) {
                            return item;
                        }
                    });

                    var args = explode.slice(1);

                    const enoughArguments = function (amount) {

                        if (args.length < amount) {
                            return;
                        }

                        let c = 0;
                        for (var i in args) {
                            if (!((args[i].length === 0 || !args[i].trim()))) {
                                c++;
                            }
                        }

                        return (c >= amount);

                    };

                    switch (command) {

                        case 'task': {

                            if (!enoughArguments(1)) {
                                this.meta.search.error = _commands[':task'].usage + ' - Missing Arguments';
                                return;
                            }

                            _tmp = _tmp.filter(function (item) {

                                if (item.id == args[0]) {
                                    return item;
                                }

                            });
                        } break;

                        case 'child': {

                            if (!enoughArguments(1)) {
                                this.meta.search.error = _commands[':child'].usage + ' - Missing Arguments';
                                return;
                            }

                            _tmp = _tmp.filter(function (item) {

                                if (item.standalone === true) {
                                    return;
                                }

                                for (var i in item.children) {
                                    if (item.children[i].id == args[0]) {
                                        return item;
                                    }
                                }

                            });
                        } break;

                        case 'progress': {

                            if (!enoughArguments(2)) {
                                this.meta.search.error = _commands[':progress'].usage + ' - Missing Arguments';
                                return;
                            }

                            var _value = parseFloat(args[1]);
                            var _operator = args[0];

                            _tmp = _tmp.filter(function (item) {

                                var _p = parseFloat((item.progress * 100).toFixed(2));

                                if (_operator === "=" && _p === _value) {
                                    return item;
                                } else if (_operator === "!=" && _p !== _value) {
                                    return item;
                                } else if (_operator === ">" && _p > _value) {
                                    return item;
                                } else if (_operator === ">=" && _p >= _value) {
                                    return item;
                                } else if (_operator === "<" && _p < _value) {
                                    return item;
                                } else if (_operator === "<=" && _p <= _value) {
                                    return item;
                                }

                            });

                        } break;

                        case 'sort': {

                            if (!enoughArguments(1)) {
                                this.meta.search.error = _commands[':sort'].usage + ' - Missing Arguments';
                                return;
                            }

                            var field   = args[0].toLowerCase();
                            var desc    = (args.length > 1 ? args[1].toLowerCase() === 'desc' : false);

                            _tmp.sort(function(a, b) {

                                switch(field) {

                                    case 'id': return (a.id - b.id);
                                    case 'progress': return (a.progress - b.progress);
                                    case 'children': return (a.children.length - b.children.length);
                                    case 'created': return (new Date(a.created_at).getTime() - new Date(b.created_at).getTime());
                                    case 'updated': return (new Date(a.updated_at).getTime() - new Date(b.updated_at).getTime());

                                }

                            });

                            if(desc) {
                                _tmp = _tmp.reverse();
                            }

                        } break;

                        default: {

                            this.meta.search.error = ('Unkown command: ' + command);

                        } break;

                    }
                }

            } else {
                //--- Normal Search
                console.log(_tmp);
                _tmp = _tmp.filter(function (item) {

                    //@HACK: Why do we have strings as keys? The Rest API is passing ints
                    //       and casting it doesn't help for some reason, weird...
                    if (
                        (statuses[item.status.id].css.button_classes['is-active'] === true || !statusMode) &&
                        (self.hasChildTypesActive(item) || self.hasTaskTypesActive(item) || !typeMode) &&
                        /*(
                            (self.meta.timeOfRelease.preAtLaunch === true && item.postLaunch === 0) ||
                            (self.meta.timeOfRelease.postLaunch === true && item.postLaunch === 1)
                        ) && TODO what is this supposed to do?*/
                        (
                            search.trim().length === 0 ||
                            item.name.toLowerCase().indexOf(search.toLowerCase()) !== -1 ||
                            item.description.toLowerCase().indexOf(search.toLocaleLowerCase()) !== -1 ||
                            self.hasChildKeyword(item) === true
                        )
                    ) {
                        console.log(item.postLaunch);
                        return item;
                    }
                });
            }

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

        axios.get(route('tasks.sortedIndex'))
            .then(response => {

                var data = response.data;

                data.forEach(function(e) {
                    e.collapsed = false;
                });

                this.container.all = data;
                //--- Load initial
                this.loadTasksPaginated();
                this.interactionBarSwitchPage('isDefault');

                this.checkForShare();
            });

    },
}
</script>
