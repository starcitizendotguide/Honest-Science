<template>
    <h1 class="title has-text-centered">Edit {{ $task->name }}</h1>
    <b-tabs v-model="settings.activeTab">
        <b-tab-item label="Pictures">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            Pellentesque vestibulum dui vel molestie egestas.
            Nulla vulputate elementum diam quis consectetur.
        </b-tab-item>

        <b-tab-item label="Music">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            Pellentesque vestibulum dui vel molestie egestas.
            Nulla vulputate elementum diam quis consectetur.
            Integer pulvinar laoreet nibh non faucibus.
            Suspendisse ut cursus lectus. Donec consectetur turpis at leo ultricies rhoncus.
            Cras consequat aliquet eros nec porta.
            Nullam sit amet mollis turpis.
            Aenean vitae tortor et velit sodales faucibus.
        </b-tab-item>

        <b-tab-item label="Videos">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            Pellentesque vestibulum dui vel molestie egestas.
            Nulla vulputate elementum diam quis consectetur.
            Integer pulvinar laoreet nibh non faucibus.
        </b-tab-item>
    </b-tabs>
</template>

<script type="text-javascript">
export default {
    data: function() {
        return {
            task: {},
            settings: {
                activeTab: 0
            }
        };
    },
    mounted: function() {
        axios.get('/api/v1/task/1')
            .then(response => {
                this.task = response.data;
            });
    },
}
</script>
