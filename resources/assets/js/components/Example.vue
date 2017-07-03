<template>
    <div class="task-list">
        <div class="field is-grouped">
            <p class="control">
                <input v-model="meta.search" class="input" placeholder="Search...">
            </p>
            <div class="field has-addons control">
                <p class="control">
                    <a v-on:click="meta.categories[0] = !meta.categories[0]" v-bind:class="{ 'is-active': isCategoryActive(0) }" class="button task-released">
                        <span class="icon is-small"><i class="fa fa-battery-4"></i></span>
                        <span>Released</span>
                    </a>
                </p>
                <p class="control">
                    <a v-on:click="meta.categories[1] = !meta.categories[1]" v-bind:class="{ 'is-active': isCategoryActive(1) }" class="button task-partially-released">
                        <span class="icon is-small"><i class="fa fa-battery-3"></i></span>
                        <span>Partially Released</span>
                    </a>
                </p>
                <p class="control">
                    <a v-on:click="meta.categories[2] = !meta.categories[2]" v-bind:class="{ 'is-active': isCategoryActive(2) }" class="button task-in-progress">
                        <span class="icon is-small"><i class="fa fa-battery-2"></i></span>
                        <span>In-Progress</span>
                    </a>
                </p>
                <p class="control">
                    <a v-on:click="meta.categories[3] = !meta.categories[3]" v-bind:class="{ 'is-active': isCategoryActive(3) }" class="button task-stagnant">
                        <span class="icon is-small"><i class="fa fa-battery-1"></i></span>
                        <span>Stagnant</span>
                    </a>
                </p>
                <p class="control">
                    <a v-on:click="meta.categories[4] = !meta.categories[4]" v-bind:class="{ 'is-active': isCategoryActive(4) }" class="button task-broken">
                        <span class="icon is-small"><i class="fa fa-chain-broken"></i></span>
                        <span>Broken</span>
                    </a>
                </p>
                <p class="control">
                    <a v-on:click="meta.categories[5] = !meta.categories[5]" v-bind:class="{ 'is-active': isCategoryActive(5) }" class="button task-cut">
                        <span class="icon is-small"><i class="fa fa-scissors"></i></span>
                        <span>Cut</span>
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
        <div class="field has-addons">
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
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th v-for="column in columns">
                        <p>{{ column | capitalize }}</p>
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="user in filteredItems" v-bind:class="statusToClass(user.status)">
                    <td>{{ user.name }}</td>
                    <td><p>{{ user.description }}</p></td>
                    <td>
                        <b-tooltip
                            label="Tooltip large multilined, because it's really long to be on a medium size. Did I tell you it's really long? Yes, it is — I asure you!"
                            position="is-bottom"
                            multilined
                            animated
                            size="is-large">
                            <span><i class="fa fa-quote-right"></i></span>
                        </b-tooltip>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script type="text-javascript">
export default {
    /**
        @TODO: Index for use in database, probably
        0 -> Released
        1 -> Partially Released
        2 -> In-Progress
        3 -> Stagnant
        4 -> Broken
        5 -> Cut

    */
    data: function() {
        return {
            meta: {
                search: '',
                categories: {
                    0: false,
                    1: false,
                    2: false,
                    3: false,
                    4: false,
                    5: false
                }
            },
            users: [
                {
                    name: 'Mission Givers',
                    description: 'Mission Givers are the plethora of human, alien, kiosk, or any number of avenues where one might obtain details to begin a mission. Although some Mission Givers may be static in their location and/or prerequisites for being obtained, some Mission Givers may seem somewhat mundane or obscure, like that discarded piece of trash you absentmindedly kicked as you were heading to a rendezvous point. A message in a bottle just might net your crew their next month’s salary.',
                    status: 0
                },
                {
                    name: 'Derelict Ships',
                    description: 'Derelict Ships, and space stations alike, are entities throughout the ‘Verse that may contain a wealth of supplies and information, or be ground zero for a ravaging epidemic. Regardless of the contents, a series of scans would go a long way to help decide whether to make the detour to check its content or radio back to a scavenging team.',
                    status: 1
                },
                {
                    name: 'Entity Owner Manager',
                    description: 'The Entity Owner Manager will track entities that are moved around the universe, making sure we spawn and despawn them at the correct time.',
                    status: 2
                },
                {
                    name: 'Render to Texture',
                    description: 'Render-to Texture will have many uses going forwards, but our focus for now is to improve UI rendering and to introduce live rendering of video communications. We’re aiming to improve rendering performance by rendering as much of the UI ahead of a frame. For video communications, this will mean that we don’t have to pre-render the comms to store those files on the hard drive (as is the case with most games). This will allow us to maintain fidelity as well as save hard drive space.',
                    status: 3
                },
                {
                    name: 'Debris Fields',
                    description: 'Debris Fields are large masses of flotsam with the potential for lucrative rewards. Was it the site of an ancient battle containing unheard of technology or materials? Maybe it was a hauler of precious minerals that taxed its engines a little too hard before they blew. Regardless of the origin, staying around too long might draw unwanted attention.',
                    status: 4
                },
                {
                    name: 'Netowrk Bind-Unbind',
                    description: '"Network Binding and Unbinding eliminates network updates for entities far away from clients. This should greatly reduce the amount of work the netcode must do, helping improve server performance. A greater proportion of client bandwidth will be spent on entities closest to the client.',
                    status: 5
                },
            ],
            columns: [
                'name',
                'description',
                'Source'
            ]
        };
    },
    methods: {
        statusToClass: function(status) {
            switch (status) {

                //@TODO Fix CSS. Hovering over the TR in a table looks, well..., 'okay'
                case 0: return { 'task-released': true };
                case 1: return { 'task-partially-released': true };
                case 2: return { 'task-in-progress': true };
                case 3: return { 'task-stagnant': true };
                case 4: return { 'task-broken': true };
                case 5: return { 'task-cut': true };

                default: return {};

            }
        },
        isCategoryActive: function(category) {
            return this.meta.categories[category];
        },
        resetMeta: function() {

            //--- Reset search value
            this.meta.search = '';

            //--- Reset categories
            for(var i = 0; i < Object.keys(this.meta.categories).length; i++) {
                this.meta.categories[i] = false;
            }

            console.log(JSON.stringify(this.meta));

        }
    },
    computed: {
        filteredItems: function () {

            var _tmp = this.users,
                search = this.meta.search,
                categories = this.meta.categories;

            var categoryMode = false;

            for(var i = 0; i < 6; i++) {
                if(categories[i] === true) {
                    categoryMode = true;
                }
            }

            if(!search && !categoryMode){
                return _tmp;
            }

            search = search.trim().toLowerCase();

            _tmp = _tmp.filter(function(item){
                if(
                    categories[item.status] === true &&
                    (
                        item.name.toLowerCase().indexOf(search) !== -1 ||
                        item.description.toLowerCase().indexOf(search) !== -1
                    )
                ){
                    return item;
                }
            })

            return _tmp;
        }
    }
}
</script>
