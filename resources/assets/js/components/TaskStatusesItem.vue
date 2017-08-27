<template>
    <b-table
        :data="statuses"
        :loading="settings.isLoading"
        :row-class="(row, index) => row.css_class"
    >
        <template scope="props">
            <b-table-column field="content" label="Status Report">
                <i :class="props.row.css_icon"></i>
                <span>{{ props.row.name }}</span>
                <span class="is-pulled-right">{{ props.row.countRelative * 100 }}%</span>
            </b-table-column>
        </template>

        <template slot="detail" scope="props">
            <article class="media">
                </figure>
                <div class="media-content">
                    <div class="content">
                        <p>{{ props.row.description }}</p>
                        <a href="#" v-if="props.row.image != null" @click="props.row.image.show = true">Show Team</a>
                    </div>
                </div>
            </article>
            <b-modal  v-if="props.row.image != null" :active.sync="props.row.image.show">
                <div class="card">
                    <div class="card-image">
                        <figure class="image">
                            <img :src="props.row.image.url_full" :alt="props.row.location">
                        </figure>
                    </div>
                </div>
            </b-modal>
        </template>

    </b-table>
</template>

<script type="text-javascript">
export default {
    data: function() {
        return {
            statuses: [],
            settings: {
                isLoading: true,
            }
        };
    },
    mounted: function() {
        axios.get(route('statuses.index'))
            .then(response => {
                this.statuses = response.data;
                console.log(this.statuses);
                this.settings.isLoading = false;
            });
    }
}
</script>
