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

            <b-table-column field="css_button" label="CSS Button">
                <button class="button" :class="props.row.css_class">Example</button>
            </b-table-column>

            <b-table-column field="css_icon" label="CSS Icon">
                <i class="fa" :class="props.row.css_icon"></i>
            </b-table-column>

            <b-table-column field="created_at" label="Created At" sortable>
                {{ props.row.created_at }}
            </b-table-column>

            <b-table-column field="updated_at" label="Updated At" sortable>
                {{ props.row.updated_at }}
            </b-table-column>

            <b-table-column label="Action">
                <a href="#"><span><i class="fa fa-pencil"></i></span></a>
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
        axios.get('/api/v1/statuses')
            .then(response => (this.statuses = response.data));
    },
}
</script>
