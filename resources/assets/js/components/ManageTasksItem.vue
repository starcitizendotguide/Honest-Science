<template>
    <b-table
        :data="tasks"
        default-sort="id"
        :striped="settings.isStriped"
        :paginated="settings.isPaginatedSimple"
        :pagination-simple="settings.isPaginatedSimple"
        :per-page="settings.perPage"
        :loading="settings.isLoading"
    >
        <template scope="props">
            <b-table-column field="id" label="#" sortable numeric>
                {{ props.row.id }}
            </b-table-column>

            <b-table-column field="name" label="Name" sortable>
                {{ props.row.name }}
            </b-table-column>

            <b-table-column field="status" label="Status" sortable>
                {{ props.row.status.name }}
            </b-table-column>

            <b-table-column field="description" label="Description" width="500" >
                {{ props.row.description }}
            </b-table-column>

            <b-table-column field="children" label="Children">
                {{ props.row.children.length }} Children
            </b-table-column>

            <b-table-column field="created_at" label="Created At" sortable>
                {{ props.row.created_at.date }}
            </b-table-column>

            <b-table-column field="updated_at" label="Updated At" sortable>
                {{ props.row.updated_at.date }}
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
            tasks: [],
            settings: {
                isStriped: true,
                isPaginatedSimple: true,
                perPage: 10,
                isLoading: true,
            }
        };
    },
    mounted: function() {
        axios.get('/api/v1/tasks')
            .then(response => {
                this.tasks = response.data;
                this.settings.isLoading = false;
            });
    },
}
</script>
