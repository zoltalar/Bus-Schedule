<template>
    <div>
        <i class="fas fa-circle-notch"></i>
        <div class="list-group">
            <a href="#" class="list-group-item" v-for="stop in stops">{{ stop.name }}</a>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'stop-list',       
        data() {
            return {
                stops: []
            }
        },
        methods: {
            load() {
                let that = this
                setTimeout(function() {
                    axios
                        .get('/stops')
                        .then(response => {
                            that.stops = response.data
                        })
                }, 2000)
            }
        },
        mounted() {
            this.load()
        }
    }
</script>