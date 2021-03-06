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
        <template slot-scope="props">
            <b-table-column field="id" label="#" sortable numeric>
                {{ props.row.id }}
            </b-table-column>

            <b-table-column field="name" label="Name" sortable>
                {{ props.row.name }}
            </b-table-column>

            <b-table-column field="status.id" label="Status" sortable>
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

            <b-table-column field="visibility.id" label="Visibility" sortable>
                {{ props.row.visibility.name }}
            </b-table-column>

            <b-table-column field="count_progress_as_one" label="CTP" sortable>
                {{ props.row.count_progress_as_one == 0 ? 'Children' : 'Single' }}
            </b-table-column>

            <b-table-column field="post_launch" label="Planned" sortable>
                {{ props.row.post_launch == 0 ? 'Pre-/At-Launch' : 'Post-Launch' }}
            </b-table-column>

            <b-table-column field="created_at" label="Created At" sortable>
                {{ props.row.created_at }}
            </b-table-column>

            <b-table-column field="updated_at" label="Updated At" sortable>
                {{ props.row.updated_at }}
            </b-table-column>

            <b-table-column label="Action">
                <a v-if="permissions.canEdit" :href="props.row.edit_url"><span><i class="fa fa-pencil"></i></span></a>
                <confirm-item
                    v-if="permissions.canDelete"
                    title="Delete Task"
                    :message="'Are you sure you want to delete ' + props.row.name + ' (' + props.row.id + ')?'"
                    positive="Delete"
                    negative="Cancel"
                    :url="props.row.delete_url"
                    theme="is-danger"
                    width=480>
                    <span><i class="fa fa-trash"></i></span>
                </confirm-item>
                <confirm-item
                    v-if="permissions.canVerify"
                    title="Mark Task as Unverified"
                    :message="'Do you really wanna mark ' + props.row.name + ' (' + props.row.id + ') as unverified?'"
                    positive="Yes"
                    negative="No"
                    :url="props.row.uncheck_url"
                    theme="is-danger"
                    width=300>
                    <span><i class="fa fa-times"></i></span>
                </confirm-item>
                <i v-if="!permissions.canEdit && !permissions.canDelete && !permissions.canVerify">
                    -
                </i>
            </b-table-column>
        </template>

        <div slot="empty" class="has-text-centered">
            No tasks available.
        </div>
    </b-table>
</template>

<script>
export default {
    props: {
        'candelete': {
            required: true,
        },
        'canedit': {
            required: true,
        },
        'canverify': {
            required: true,
        }
    },
    data: function() {
        return {
            tasks: [],
            permissions: {
                canDelete: this.candelete,
                canEdit: this.canedit,
                canVerify: this.canverify,
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
                    item.uncheck_url = route('manage.content.tasks.verify.uncheck', {'id': item.id});
                });

                this.settings.isLoading = false;
            });
    }
}
</script>
