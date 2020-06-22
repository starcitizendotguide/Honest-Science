<template>
    <b-table
        :data="users"
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

            <b-table-column field="rsi_handle" label="RSI Handle" sortable>
                {{ props.row.name }}
            </b-table-column>

            <b-table-column field="email" label="Email" sortable>
                {{ props.row.email }}
            </b-table-column>

            <b-table-column label="Role">
                {{ props.row.roles[0].display_name }}
            </b-table-column>

            <b-table-column field="created_at.date" label="Created At" sortable>
                {{ props.row.created_at.date }}
            </b-table-column>

            <b-table-column field="updated_at.date" label="Updated At" sortable>
                {{ props.row.updated_at.date }}
            </b-table-column>

            <!--<b-table-column label="Action">
                <a :href="props.row.edit_url"><span><i class="fa fa-pencil"></i></span></a>
            </b-table-column>-->
        </template>
    </b-table>
</template>

<script type="text-javascript">
export default {
    data: function() {
        return {
            users: [],
            settings: {
                isStriped: true,
                isPaginatedSimple: true,
                perPage: 10,
                isLoading: true,
            }
        };
    },
    mounted: function() {
        axios.get(route('users.index'))
            .then(response => {
                this.users = response.data;

                this.users.forEach(function(item) {
                    item.edit_url = route('manage.content.user.edit', {'id': item.id});
                });

                this.settings.isLoading = false;
            });
    }
}
</script>
