<template>
    <b-table
        :data="logEntries"
        default-sort="id"
        :loading="settings.isLoading"
        detailed
    >

        <template slot-scope="props">
            <b-table-column field="id" label="ID" centered>
                {{ props.row.id }}
            </b-table-column>

            <b-table-column field="name" label="Name" centered>
                {{ props.row.name }}
            </b-table-column>

            <b-table-column field="logged" label="Logged" centered>
                {{ props.row.logged }}
            </b-table-column>

        </template>

        <template slot-scope="detail">
            <article class="media">
                <div class="media-content">
                    <div class="content">
                        <p>{{ props.row.message }}</p>
                    </div>
                </div>
            </article>
        </template>
    </b-table>
</template>

<script type="text-javascript">
export default {
    props: {
        log: {
            required: true,
        },
        entries: {
            required: false,
            default: 10,
        }
    },
    data: function() {
        return {
            logEntries: [],
            settings: {
                isLoading: true,
            }
        };
    },
    mounted: function() {

        axios.get(route('log.entries', { entry: this.log, limit: this.entries }))
            .then(response => {

                this.logEntries = response.data;
                this.settings.isLoading = false;

            });

    }
}
</script>
