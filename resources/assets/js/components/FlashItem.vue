<template></template>

<script type="text-javascript">
export default {
    props: {
        notifier: {
            required: true,
        },
        message: {
            required: true,
        },
        duration: {
            default: 3500
        },
        position: {
            default: 'is-top'
        },
        type: {
            default: 'is-primary'
        }
    },
    mounted: function() {

        var config = {
            duration: this.duration,
            message: this.message,
            position: this.position,
            type: this.type
        };

        switch(this.notifier) {

            case 'toast':
                this.$toast.open(config);
                break;

            case 'snackbar': {
                this.$snackbar.open(config);
            }
            break;

        }


    }
}
</script>
