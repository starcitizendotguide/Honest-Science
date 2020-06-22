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

            <b-table-column field="created_at.date" label="Created At" sortable>
                {{ props.row.created_at.date }}
            </b-table-column>

            <b-table-column field="updated_at.date" label="Updated At" sortable>
                {{ props.row.updated_at.date }}
            </b-table-column>

            <b-table-column label="Action">
                <a v-if="permissions.canEdit" :href="props.row.edit_url"><span><i class="fa fa-pencil"></i></span></a>
                <task-preview-item :taskid="props.row.id"><span><i class="fa fa-eye"></i></span></task-preview-item>
                <confirm-item
                    v-if="permissions.canVerify"
                    title="Mark Task as Verified"
                    :message="'Did you verify the content of ' + props.row.name + ' (' + props.row.id + ')?'"
                    positive="Yes"
                    negative="No"
                    :url="props.row.checked_url"
                    theme="is-success"
                    width=300>
                    <span><i class="fa fa-check"></i></span>
                </confirm-item>
            </b-table-column>
        </template>

        <div slot-scope="empty" class="has-text-centered">
            No tasks in the queue. Awesome!
        </div>
    </b-table>
</template>

<script type="text-javascript">
export default {
    props: {
        canedit: {
            required: true,
        },
        canverify: {
            required: true,
        }
    },
    data: function() {
        return {
            tasks: [],
            permissions: {
                canEdit: this.canedit,
                canVerify: this.canverify
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
        axios.get(route('tasks.verifyQueue'))
            .then(response => {
                this.tasks = response.data;
                this.tasks.forEach(function(item) {
                    if(item.standalone === true) {
                        item.edit_url = route('manage.content.tasks.edit_standalone', {'id': item.id});
                    } else {
                        item.edit_url = route('manage.content.tasks.edit', {'id': item.id});
                    }

                    item.checked_url = route('manage.content.tasks.verify.checked', {'id': item.id});
                });

                this.settings.isLoading = false;
            });
    }
}
</script>
