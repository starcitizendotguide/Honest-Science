<template>
    <span>
        <span class="clickable" @click="meta.isActive = true"><slot></slot></span>
        <b-modal :active.sync="meta.isActive" :width="width">
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">{{ title }}</p>
                </header>
                <section class="modal-card-body">
                    <p class="has-text-black">{{ message }}</p>
                </section>
                <footer class="modal-card-foot">
                    <a :href="url" class="button" :class="theme">{{ positive }}</a>
                    <button class="button" type="button" @click="meta.isActive = false">{{ negative }}</button>
                </footer>
            </div>
        </b-modal>
    </span>
</template>

<script>
export default {
    props: {
        title: String,
        positive: String,
        negative: String,
        url: String,
        message: String,
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
