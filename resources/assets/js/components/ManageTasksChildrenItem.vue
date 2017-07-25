<template>
    <b-table
        :data="children"
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

            <b-table-column field="name" label="Parent" sortable>
                {{ props.row.task_id }}
            </b-table-column>

            <b-table-column field="status" label="Status" sortable>
                {{ props.row.status.name }}
            </b-table-column>

            <b-table-column field="type" label="Type" sortable>
                {{ props.row.type }}
            </b-table-column>

            <b-table-column field="description" label="Description" width="500" >
                {{ props.row.description }}
            </b-table-column>

            <b-table-column field="progres" label="Progress">
                {{ props.row.progress * 100 }}%
            </b-table-column>

            <b-table-column field="created_at" label="Created At" sortable>
                {{ props.row.created_at.date }}
            </b-table-column>

            <b-table-column field="updated_at" label="Updated At" sortable>
                {{ props.row.updated_at.date }}
            </b-table-column>
        </template>

        <div slot="empty" class="has-text-centered">
            This task has no children.
        </div>
    </b-table>
</template>

<script type="text-javascript">
export default {
    props: ['taskid'],
    data: function() {
        return {
            children: [],
            settings: {
                isStriped: true,
                isPaginatedSimple: true,
                perPage: 10,
                isLoading: true,
            }
        };
    },
    mounted: function() {
        axios.get('/api/v1/children/task/' + this.taskid)
            .then(response => {
                this.children = response.data;
                this.settings.isLoading = false;
            });
    }
}
</script>
