<template>
    <span>
        <a href="#" @click="open"><slot></slot></a>
        <b-modal :active.sync="isActive">
            <div class="card">
                <div v-if="loadedData" class="card-content">
                    <div class="media">
                        <div class="media-content">
                            <p class="title is-4">{{ task.name }}</p>
                            <p class="subtitle is-6">{{ task.status.name }}</p>
                        </div>
                    </div>

                    <div class="content">
                        {{ task.description }}
                        <br>
                        <small>{{ task.created_at.date }}</small>
                    </div>
                </div>
                <div v-else class="card-content">
                    <div class="content is-centered">
                        <span>
                            Loading...
                            <i class="loader"></i>
                        </span>
                    </div>
                </div>
            </div>
        </b-modal>
    </span>
</template>

<script>
    export default {
        props: {
            taskid: {
                required: true,
            }
        },
        data: function() {
            return {
                task: {
                    name: '-',
                    status: { name: '-' },
                    description: '-',
                    created_at: { date: '-' },
                },
                loadedData: false,
                isActive: false,
            };
        },
        methods: {
            open: function() {

                if(this.loadedData === false) {
                    this.loadData();
                }

                this.isActive = true;
            },
            loadData: function() {
                axios.get(route('tasks.show', { id: this.taskid }))
                    .then(response => {
                        this.task = response.data;
                        this.loadedData = true;
                    });
            }
        },
    }
</script>
