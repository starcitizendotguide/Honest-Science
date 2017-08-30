<template>
    <b-table
        :data="statuses"
        default-sort="id"
        :striped="settings.isStriped"
        :paginated="settings.isPaginatedSimple"
        :pagination-simple="settings.isPaginatedSimple"
        :per-page="settings.perPage"
    >
        <template scope="props">
            <b-table-column field="id" label="#" sortable numeric>
                {{ props.row.id }}
            </b-table-column>

            <b-table-column field="name" label="Name" sortable>
                {{ props.row.name }}
            </b-table-column>

            <b-table-column field="rating" label="Rating" sortable>
                {{ props.row.rating }}
            </b-table-column>

            <b-table-column field="css_icon" label="CSS Icon">
                <b-tooltip :label="props.row.css_icon" dashed>
                    <i class="fa" :class="props.row.css_icon"></i>
                </b-tooltip>
            </b-table-column>

            <b-table-column field="created_at" label="Created At" sortable>
                {{ props.row.created_at }}
            </b-table-column>

            <b-table-column field="updated_at" label="Updated At" sortable>
                {{ props.row.updated_at }}
            </b-table-column>

        </template>
    </b-table>
</template>

<script type="text-javascript">
export default {
    data: function() {
        return {
            statuses: [],
            settings: {
                isStriped: true,
                isPaginatedSimple: true,
                perPage: 10
            }
        };
    },
    mounted: function() {
        axios.get(route('statuses.index'))
            .then(response => (this.statuses = response.data));
    },
}
</script>
