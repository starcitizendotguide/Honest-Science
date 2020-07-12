<template>
    <b-table
            :items="statuses"
            :loading="settings.isLoading"
            :row-class="(row, index) => 'row'"
            class="dark-table highlighted-element"
    >
        <template slot-scope="props">
            <b-table-column field="name" label="Status Report">
                <i :class="props.row.css_icon"></i>
                <span>{{ props.row.name }}</span>
                <span class="is-pulled-right is-dashed">{{ props.row.countRelative * 100 | toFixed(2) }}%</span>
            </b-table-column>
        </template>

    </b-table>
</template>

<script>
export default {
    data: function() {
        return {
            statuses: [],
            settings: {
                isLoading: true,
            }
        };
    },
    mounted: function() {
        axios.get(route('statuses.index'))
            .then(response => {
                this.statuses = response.data;
                this.settings.isLoading = false;
            });
    }
}
</script>
