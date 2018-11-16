<template>
    <div id="stop">
        <h5>{{ name }}</h5>
        <stop-route></stop-route>
    </div>
</template>
<script>
    export default {
        name: 'stop',
        data() {
            return {
                name: ''
            }
        },        
        computed: {
            selectedStop() {
                return this.$store.getters.stop
            }
        },
        watch: {
            'selectedStop.name': function(newVal, oldVal) {
                this.name = newVal
            }
        }
    }
</script>