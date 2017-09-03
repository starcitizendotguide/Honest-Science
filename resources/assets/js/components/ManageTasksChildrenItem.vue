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
            <b-table-column field="name" label="Name" sortable>
                {{ props.row.name }}
            </b-table-column>
            <b-table-column field="status.id" label="Status" sortable>
                {{ props.row.status.name }}
            </b-table-column>

            <b-table-column field="type.id" label="Type" sortable>
                {{ props.row.type.name }}
            </b-table-column>

            <b-table-column field="description" label="Description" width="400" >
                {{ props.row.description | truncate(50) }}
            </b-table-column>

            <b-table-column field="progress" label="Progress">
                {{ props.row.progress * 100 | toFixed(2) }}%
            </b-table-column>

            <b-table-column field="created_at.date" label="Created At" sortable>
                {{ props.row.created_at.date }}
            </b-table-column>

            <b-table-column field="updated_at.date" label="Updated At" sortable>
                {{ props.row.updated_at.date }}
            </b-table-column>

            <b-table-column label="Action">
                <a v-if="permissions.canEdit" :href="props.row.edit_url"><span><i class="fa fa-pencil"></i></span></a>
                <confirm-item
                    v-if="permissions.canDelete"
                    title="Delete Child"
                    :message="'Are you sure you want to delete ' + props.row.name + ' (' + props.row.id + ')?'"
                    positive="Delete"
                    negative="Cancel"
                    :url="props.row.delete_url"
                    theme="is-danger"
                    width=480
                >
                    <span><i class="fa fa-trash"></i></span>
                </confirm-item>
                <span v-if="!permissions.canEdit && !permissions.canDelete">-</span>
            </b-table-column>

        </template>
        <div slot="empty" class="has-text-centered">
            This task has no children.
        </div>
    </b-table>
</template>

<script type="text-javascript">
export default {
    props: {
        taskid: {
            required: true,
        },
        canedit: {
            required: true,
        },
        candelete: {
            required: true,
        }
    },
    data: function() {
        return {
            children: [],
            permissions: {
                canEdit: this.canedit,
                canDelete: this.candelete,
            },
            settings: {
                isStriped: true,
                isPaginatedSimple: true,
                perPage: 10,
                isLoading: true,
            }
        };
    },
    mounted: function() {
        axios.get(route('children.task.show', {id: this.taskid}))
            .then(response => {
                this.children = response.data;

                this.children.forEach(function(item) {
                    item.edit_url = route('manage.content.child.edit', {'id': item.id});
                    item.delete_url = route('manage.content.child.delete', {'id': item.id});
                });

                this.settings.isLoading = false;
            });

    }
}
</script>
