<template>
    <b-table
        :data="groups"
        default-sort="id"
        :striped="settings.isStriped"
        :paginated="settings.isPaginatedSimple"
        :pagination-simple="settings.isPaginatedSimple"
        :per-page="settings.perPage"
        :loading="settings.isLoading"
        detailed
    >

        <template slot-scope="props">
            <b-table-column field="id" label="id">
                {{ props.row.name }}
            </b-table-column>

            <b-table-column field="name" label="Name" centered>
                <b-tooltip
                    :label="props.row.display_name"
                    dashed
                >
                    {{ props.row.name }}
                </b-tooltip>
            </b-table-column>

            <b-table-column field="created_at.date" label="Created At" sortable>
                {{ props.row.created_at.date }}
            </b-table-column>

            <b-table-column field="updated_at.date" label="Updated At" sortable>
                {{ props.row.updated_at.date }}
            </b-table-column>

        </template>

        <template slot="detail">
            <table class="table">
                <thead>
                    <tr>
                        <th><abbr title="Position">#</abbr></th>
                        <th>Name</th>
                        <th>Permission</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="permission in props.row.permissions">
                        <td>{{ permission.id }}</td>
                        <td>{{ permission.display_name }}</td>
                        <td>{{ permission.name }}</td>
                    </tr>
                </tbody>
            </table>
        </template>
    </b-table>
</template>

<script type="text-javascript">
export default {
    data: function() {
        return {
            groups: [],
            settings: {
                isStriped: true,
                isPaginatedSimple: true,
                perPage: 10,
                isLoading: true,
            }
        };
    },
    mounted: function() {
        axios.get(route('groups.index'))
            .then(response => {
                this.groups = response.data;
                this.settings.isLoading = false;
            });
    }
}
</script>
