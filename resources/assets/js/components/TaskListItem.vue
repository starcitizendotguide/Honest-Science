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
                    <article class="media">
                        <div class="media-content">
                            <div class="content">
                                <p>
                                    <strong>{{ task.name }}</strong> -
                                    <b-tooltip :label="task.status.name + ' - ' + toFixed(task.progress * 100, 2) + '%'" type="is-dark" square dashed animated>
                                        <span class="icon is-small"><i :class="task.status.css_icon"></i></span>
                                    </b-tooltip>
                                    <b-tooltip v-if="task.standalone" :label="task.type.name" type="is-dark" square dashed animated>
                                        <span class="icon is-small"><i :class="task.type.css_icon"></i></span>
                                    </b-tooltip>

                                    <span><i class="fa is-focused is-pulled-right" v-bind:class="{ 'fa-arrow-right': !task.collapsed, 'fa-arrow-down': task.collapsed }"></i></span>

                                    <p v-if="!task.collapsed">{{ task.description | truncate(120) }}</p>
                                    <transition name="fade">
                                        <p v-if="task.collapsed">{{ task.description }}</p>
                                    </transition>

                                    <progress class="progress" :value="task.progress" max="1">{{ toFixed(task.progress * 100, 2) }}%</progress>

                                    <span v-if="task.standalone" class="is-pulled-right ignore-click">
                                        <confirm-item
                                            v-for="(source, index) in task.sources"
                                            :key="source.id"

                                            title="You are leaving Star Citizen - Honest Science."
                                            :message="source.link + ' is not an official Honest Science site.'"
                                            positive="Continue to external site."
                                            negative="Cancel"
                                            :url="source.link"
                                            theme="is-danger"
                                            width=960
                                        >
                                            [{{ index + 1 }}]
                                        </confirm-item>
                                    </span>
                                </p>
                                <transition name="fade">
                                    <div v-if="task.collapsed" class="ignore-click">
                                        <div class="box highlighted-element" v-for="child in task.children">
                                            <article class="media">
                                                <div class="media-content">
                                                    <div class="content">
                                                        <strong class="">{{ child.name }}</strong> -
                                                        <b-tooltip :label="child.status.name + ' - ' + toFixed(child.progress * 100, 2) + '%'" type="is-dark" square dashed animated>
                                                            <span class="icon is-small "><i :class="child.status.css_icon"></i></span>
                                                        </b-tooltip>
                                                        <b-tooltip :label="child.type.name" type="is-dark" square dashed animated>
                                                            <span class="icon is-small"><i :class="child.type.css_icon"></i></span>
                                                        </b-tooltip>
                                                        <br />
                                                        {{ child.description }}
                                                        <br />
                                                        <span class="is-pulled-right">
                                                            <confirm-item
                                                                v-for="(source, index) in child.sources"
                                                                :key="source.id"

                                                                title="You are leaving Star Citizen - Honest Science."
                                                                :message="source.link + ' is not an official Honest Science site.'"
                                                                positive="Continue to external site."
                                                                negative="Cancel"
                                                                :url="source.link"
                                                                theme="is-danger"
                                                                width=960
                                                            >
                                                                [{{ index + 1 }}]
                                                            </confirm-item>
                                                        </span>
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
            </div>
        </div>
        <!-- Interaction Bar -->
        <div class="column is-3 m-t-100">
            <div class="card card-content interaction-bar highlighted-element" :class="{'is-active': (meta.interactionBar.task !== null)}" v-html="meta.interactionBar.content"></div>
        </div>
    </div>
</template>

<script type="text-javascript">
export default {
    data: function() {
        return {
            tasks: [],
            meta: {
                search: '',
                interactionBar: {
                    task: null,
                    content: this.defaultInteractionBar()
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
        //--- Resets the interaction bar to its default status.
        resetInteractionBar() {
            this.meta.interactionBar.task = null;
            this.meta.interactionBar.content = this.defaultInteractionBar();
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
                this.loadDisqus(task);
            } else {
                this.resetInteractionBar();
            }

        },
        //--- Loads the Disqus section for an task.
        loadDisqus(task) {

            this.meta.interactionBar.content = '<div class="disquscard-content" id="disqus_thread"></div>';

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


                    s.setAttribute('data-timestamp', +new Date());
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
        //---- Help function: returns the default value of the interaction bar.
        defaultInteractionBar() {
            return '<p>This is our interactive bar. You can open any task to test its behaviour.</p>';
        },
        //--- Help Fuction: formations a number using fixed-point notation.
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
            var _tmp        = this.tasks,
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
                return _tmp;
            }

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

        axios.get(route('tasks.index'))
            .then(response => {

                var data = response.data;

                data.forEach(function(e) {
                    e.collapsed = false;
                });

                this.tasks = data;

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
    },
}
</script>
