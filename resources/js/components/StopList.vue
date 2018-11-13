<template>
    <div id="stops">
        <div class="spinner" v-show="loading === true">
            <i class="fas fa-sync fa-spin"></i>
        </div>
        <form>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Search..." :disabled="loading === true">
            </div>
        </form>
        <div class="list-group" v-show="loading === false">
            <a href="#" class="list-group-item" v-for="stop in stops">{{ stop.name }}</a>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'stop-list',       
        data() {
            return {
                loading: true,
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
                            that.loading = false
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