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

            <b-table-column field="description" label="Description" width="400" >
                {{ props.row.description | truncate(50) }}
            </b-table-column>

            <b-table-column field="children" label="Children">
                <span v-if="props.row.standalone">-</span>
                <span v-else>{{ props.row.children.length }} {{ props.row.children.length > 1 ? 'Children' : 'Child' }}</span>
            </b-table-column>

            <b-table-column field="verified" label="Verified" sortable>
                {{ props.row.verified ? 'Yes' : 'No' }}
            </b-table-column>

            <b-table-column field="visibility" label="Visibility" sortable>
                {{ props.row.visibility.name }}
            </b-table-column>

            <b-table-column field="created_at" label="Created At" sortable>
                {{ props.row.created_at.date }}
            </b-table-column>

            <b-table-column field="updated_at" label="Updated At" sortable>
                {{ props.row.updated_at.date }}
            </b-table-column>

            <b-table-column label="Action">
                <a :href="props.row.edit_url"><span><i class="fa fa-pencil"></i></span></a>
                <confirm-item
                    title="Delete Task"
                    :message="'Are you sure you want to delete ' + props.row.name + ' (' + props.row.id + ')?'"
                    positive="Delete"
                    negative="Cancel"
                    :url="props.row.delete_url"
                    theme="is-danger"
                    width=480
                >
                    <span><i class="fa fa-trash"></i></span>
                </confirm-item>
            </b-table-column>
        </template>

        <div slot="empty" class="has-text-centered">
            No tasks available.
        </div>
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
        axios.get(route('tasks.index'))
            .then(response => {
                this.tasks = response.data;

                this.tasks.forEach(function(item) {
                    if(item.standalone === true) {
                        item.edit_url = route('manage.content.tasks.edit_standalone', {'id': item.id});
                    } else {
                        item.edit_url = route('manage.content.tasks.edit', {'id': item.id});
                    }

                    item.delete_url = route('manage.content.tasks.delete', {'id': item.id});
                });

                this.settings.isLoading = false;
            });
    }
}
</script>
