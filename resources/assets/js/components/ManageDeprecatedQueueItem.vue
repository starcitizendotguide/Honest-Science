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

            <b-table-column field="status.id" label="Status" sortable>
                {{ props.row.status.name }}
            </b-table-column>

            <b-table-column field="description" label="Description" width="400" >
                {{ props.row.description | truncate(50) }}
            </b-table-column>

            <b-table-column field="updated_at.date" label="Updated At" sortable>
                {{ props.row.updated_at.date }}
            </b-table-column>

            <b-table-column label="Action">
                <a v-if="permissions.canEdit" :href="props.row.edit_url"><span><i class="fa fa-pencil"></i></span></a>
                <confirm-item
                    v-if="permissions.canCheck"
                    title="Mark Task as Update-to-Date"
                    :message="'Is all of the available data for ' + props.row.name + ' (' + props.row.id + ') up to date?'"
                    positive="Yes"
                    negative="No"
                    :url="props.row.checked_url"
                    theme="is-success"
                    width=300>
                    <span><i class="fa fa-check"></i></span>
                </confirm-item>
            </b-table-column>
        </template>

        <div slot="empty" class="has-text-centered">
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
        cancheck: {
            required: true,
        }
    },
    data: function() {
        return {
            tasks: [],
            permissions: {
                canEdit: this.canedit,
                canCheck: this.cancheck
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
        axios.get(route('tasks.deprecatedQueue'))
            .then(response => {
                this.tasks = response.data;

                this.tasks.forEach(function(item) {
                    if(item.standalone === true) {
                        item.edit_url = route('manage.content.tasks.edit_standalone', {'id': item.id});
                    } else {
                        item.edit_url = route('manage.content.tasks.edit', {'id': item.id});
                    }

                    item.checked_url = route('manage.content.tasks.deprecated.checked', {'id': item.id});
                });

                this.settings.isLoading = false;
            });
    }
}
</script>
