<template>
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
                            <span><i v-on:click="task.collapsed = !task.collapsed" class="fa is-focused is-pulled-right" v-bind:class="{ 'fa-arrow-right': !task.collapsed, 'fa-arrow-down': task.collapsed }"></i></span>
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
</template>

<script type="text-javascript">
export default {
    data: function() {
        return {
            tasks: [],
            meta: {
                search: '',
                statuses: [],
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

        },
        toFixed(value, digits) {
            return value.toFixed(digits);
        },
        categoryOnClick(id) {
            this.meta.statuses[id].css.button_classes['is-active'] = !this.meta.statuses[id].css.button_classes['is-active'];
        }
    },
    computed: {
        filteredItems: function () {
            var _tmp = this.tasks,
                search = this.meta.search,
                statuses = this.meta.statuses,

                self = this,

                statusById = function(id) {
                    statuses.forEach(function(e) {
                        console.log(e);
                    })
                };



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
        axios.get('/api/v1/statuses')
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
                    };
                    tmp.css.button_classes[e.css_class] = true;
                    $this.meta.statuses.push(tmp);

                });

            });

        axios.get('/api/v1/tasks')
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
