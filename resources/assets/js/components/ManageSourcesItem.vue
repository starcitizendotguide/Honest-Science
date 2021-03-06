<template>
    <b-table
        :data="sources"
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
            <b-table-column field="link" label="Link" sortable>
                {{ props.row.link }}
            </b-table-column>

            <b-table-column field="created_at" label="Created At" sortable>
                {{ props.row.created_at }}
            </b-table-column>

            <b-table-column field="updated_at" label="Updated At" sortable>
                {{ props.row.updated_at }}
            </b-table-column>

            <b-table-column label="Action">
                <confirm-item
                    v-if="permissions.canDelete"
                    title="Delete Source"
                    :message="'Are you sure you want to delete ' + props.row.link + ' (' + props.row.id + ')?'"
                    positive="Delete"
                    negative="Cancel"
                    :url="props.row.delete_url"
                    theme="is-danger"
                    width=960
                >
                    <span><i class="fa fa-trash"></i></span>
                </confirm-item>
                <span v-if="!permissions.canDelete">-</span>
            </b-table-column>

        </template>
        <div slot-scope="empty" class="has-text-centered">
            This task has no sources.
        </div>
    </b-table>
</template>

<script type="text-javascript">
export default {
    props: {
        objectid: {
            required: true,
        },
        standalone: {
            required: true,
        },
        candelete: {
            required: true,
        },
    },
    data: function() {
        return {
            sources: [],
            permissions: {
                canDelete: this.candelete
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
        axios.get(route('children.sources.show', {id: this.objectid, standalone: this.standalone}))
            .then(response => {
                this.sources = response.data;

                this.sources.forEach(function(item) {
                    item.delete_url = route('manage.content.source.delete', {id: item.id});
                });

                this.settings.isLoading = false;
            });

    }
}
</script>
