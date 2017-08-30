<template>
    <span>
        <a href="#" @click="meta.isActive = true"><slot></slot></a>
        <b-modal :active.sync="meta.isActive" :width="width">
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">{{ title }}</p>
                </header>
                <section class="modal-card-body">
                    <p :class="theme">{{ message }}</p>
                </section>
                <footer class="modal-card-foot">
                    <a :href="url" class="button" :class="theme">{{ positive }}</a>
                    <button class="button" type="button" @click="meta.isActive = false">{{ negative }}</button>
                </footer>
            </div>
        </b-modal>
    </span>
</template>

<script type="text-javascript">
export default {
    props: {
        title: String,
        message: String,
        positive: String,
        negative: String,
        url: String,
        theme: {
            type: String,
            default: 'is-primary'
        },
        width: {
            default: 480
        }
    },
    data: function() {
        return {
            meta: {
                isActive: false
            }
        };
    },
}
</script>
