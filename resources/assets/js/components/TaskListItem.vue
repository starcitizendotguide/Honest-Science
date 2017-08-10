<template>
    <div class="columns">
        <div class="column is-offset-1 is-7">
            <div class="task-list">
                <div class="field is-grouped">
                    <p class="control">
                        <input v-model="meta.search" class="input" placeholder="Search...">
                    </p>
                    <div class="field has-addons control">
                        <p class="control" v-for="status in meta.statuses">
                            <a v-on:click="categoryOnClick(status.id)" class="button" v-bind:class="status.css.button_classes" >
                               <span class="icon is-small"><i :class="status.css.icon"></i></span>
                               <span>{{ status.name }}</span>
                           </a>
                        </p>
                    </div>
                    <p class="control is-pulled-right">
                        <a v-on:click="resetMeta" class="button">
                            <span class="icon is-small"><i class="fa fa-undo"></i></span>
                            <span>Reset</span>
                        </a>
                    </p>
                </div>
                <!--<div class="field has-addons">
                    <p class="control"><a href="#" class="button"><span class="icon is-small"><i class="fa fa-rocket"></i></span><span>Example</span></a></p>
                    <p class="control"><a href="#" class="button"><span class="icon is-small"><i class="fa fa-rocket"></i></span><span>Example</span></a></p>
                    <p class="control"><a href="#" class="button"><span class="icon is-small"><i class="fa fa-rocket"></i></span><span>Example</span></a></p>
                    <p class="control"><a href="#" class="button"><span class="icon is-small"><i class="fa fa-rocket"></i></span><span>Example</span></a></p>
                    <p class="control"><a href="#" class="button"><span class="icon is-small"><i class="fa fa-rocket"></i></span><span>Example</span></a></p>
                    <p class="control"><a href="#" class="button"><span class="icon is-small"><i class="fa fa-rocket"></i></span><span>Example</span></a></p>
                    <p class="control"><a href="#" class="button"><span class="icon is-small"><i class="fa fa-rocket"></i></span><span>Example</span></a></p>
                    <p class="control"><a href="#" class="button"><span class="icon is-small"><i class="fa fa-rocket"></i></span><span>Example</span></a></p>
                    <p class="control"><a href="#" class="button"><span class="icon is-small"><i class="fa fa-rocket"></i></span><span>Example</span></a></p>
                    <p class="control"><a href="#" class="button"><span class="icon is-small"><i class="fa fa-rocket"></i></span><span>Example</span></a></p>
                    <p class="control"><a href="#" class="button"><span class="icon is-small"><i class="fa fa-rocket"></i></span><span>Example</span></a></p>
                    <p class="control"><a href="#" class="button"><span class="icon is-small"><i class="fa fa-rocket"></i></span><span>Example</span></a></p>
                    <p class="control"><a href="#" class="button"><span class="icon is-small"><i class="fa fa-rocket"></i></span><span>Example</span></a></p>
                </div>-->


                <div class="box" v-for="task in filteredItems">
                    <article class="media">
                        <div class="media-left">
                            <figure class="image is-64x64">
                                <img src="http://bulma.io/images/placeholders/128x128.png" alt="Image">
                            </figure>
                        </div>
                        <div class="media-content">
                            <div class="content">
                                <p>
                                    <strong>{{ task.name }}</strong>
                                    <small>{{ task.status.name }}</small>
                                    <small>{{ toFixed(task.progress * 100, 2) }}%</small>
                                    <span><i v-on:click="triggerCollapse(task)" class="fa is-focused is-pulled-right" v-bind:class="{ 'fa-arrow-right': !task.collapsed, 'fa-arrow-down': task.collapsed }"></i></span>
                                    <br />
                                    {{ task.description }}
                                    <br />
                                    <progress class="progress" :value="task.progress" max="1">{{ toFixed(task.progress * 100, 2) }}%</progress>
                                </p>
                                <transition name="fade">
                                    <div v-if="task.collapsed">

                                        <div class="box" v-bind:class="child.status.css_class" v-for="child in task.children">
                                            <article class="media">
                                                <div class="media-left">
                                                    <figure class="image is-64x64">
                                                        <img src="http://bulma.io/images/placeholders/128x128.png" alt="Image">
                                                    </figure>
                                                </div>
                                                <div class="media-content">
                                                    <div class="content">
                                                        <strong>{{ child.name }}</strong>
                                                        <small>{{ child.status.name }}</small>
                                                        <small>{{ toFixed(child.progress * 100, 2) }}%</small>
                                                        <br />
                                                        {{ child.description }}
                                                        <br />
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
        <div class="column is-3 m-t-50">
            <div class="card card-content" v-html="meta.interactionBar.content"></div>
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
                    id: null,
                    task: null,
                    content: this.defaultInteractionBar()
                },
                statuses: []
            },
        };
    },
    methods: {
        resetMeta: function() {

            //--- Reset Collapse
            this.tasks.map(v => { v.collapsed = false;  });

            //--- Reset search value
            this.meta.search = '';

            //--- Reset categories
            for(var i = 0; i < this.meta.statuses.length; i++) {
                this.meta.statuses[i].css.button_classes['is-active'] = false;
            }

            //--- Reset Interaction Bar
            this.resetInteractionBar();
        },
        toFixed(value, digits) {
            return value.toFixed(digits);
        },
        categoryOnClick(id) {
            this.meta.statuses[id].css.button_classes['is-active'] = !this.meta.statuses[id].css.button_classes['is-active'];
        },
        triggerCollapse(task) {
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
        defaultInteractionBar() {
            return '<p>This is our interactive bar. You can open any task to test its behaviour.</p>';
        },
        resetInteractionBar() {
            this.meta.interactionBar.task = null;
            this.meta.interactionBar.content = this.defaultInteractionBar();
        },
        loadDisqus(task) {

            this.meta.interactionBar.content = '<div class="disquscard-content" id="disqus_thread"></div>';

            var CONF_SHORTNAME      = 'starcitizen-tasks';
            var CONF_IDENTIFIER     = (task.id + task.created_at.date.replace(' ', '') + '-task-id');
            var CONF_URL            = ('http://tracker.starcitizen.guide/' + CONF_IDENTIFIER + '-url');
            var CONF_TITLE          = (task.name + ' - Discussion Test');

            //--- First time loading the Disqus widget requires more setup
            if(typeof DISQUS === 'undefined') {
                window.disqus_config = function () {
                    this.page.identifier = CONF_IDENTIFIER;
                    this.page.title = CONF_TITLE;
                    this.page.url = CONF_URL;
                };

                //--- Appened Disqus Widget
                var d = document, s = d.createElement('script');
                s.src = '//' + CONF_SHORTNAME + '.disqus.com/embed.js';
                s.setAttribute('data-timestamp', +new Date());
                (d.head || d.body).appendChild(s);

            }
            //--- Disqus widget already loaded... just reset the values and load
            //    a different discussion
            else {

                //--- Appened Disqus Widget
                var d = document, s = d.createElement('script');
                s.src = '//' + CONF_SHORTNAME + '.disqus.com/embed.js';
                s.setAttribute('data-timestamp', +new Date());
                (d.head || d.body).appendChild(s);

                DISQUS.reset({
                  reload: true,
                  config: function () {
                      this.page.identifier = CONF_IDENTIFIER;
                      this.page.title = CONF_TITLE;
                      this.page.url = CONF_URL;
                  }
                });
            }
        }
    },
    computed: {
        filteredItems: function () {
            var _tmp = this.tasks,
                search = this.meta.search,
                statuses = this.meta.statuses;


            //--- Going through all "status buttons" to decide whether or not, we
            // have to include this in our search.
            var statusMode = false;
            for (var key in statuses) {
                if(statuses[key].css.button_classes['is-active'] === true) {
                    statusMode = true;
                }
            }

            if(!search && !statusMode){
                return _tmp;
            }

            search = search.trim().toLowerCase();

            _tmp = _tmp.filter(function(item){

                //--- We wanna display features that have an 'Unknown' status but
                //    we don't allow it to be filtered
                if(item.status.id < 0) {
                    return;
                }

                //@HACK: Why do we have strings as keys? The Rest API is passing ints
                //       and casting it doesn't help for some reason, weird...
                if(
                    (statuses[String(item.status.id)].css.button_classes['is-active'] === true || !statusMode) &&
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
                var categories = [];
                var $this = this;
                data.forEach(function(e) {

                    if(e.id < 0) {
                        return;
                    }

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
    },
}
</script>
