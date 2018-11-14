<template>
    <div id="stops">
        <div class="spinner" v-show="loaded === false || busy === true">
            <i class="fas fa-sync fa-spin"></i>
        </div>
        <form>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Search..." v-model="phrase" @keyup="search" :disabled="loaded === false">
            </div>
        </form>
        <div class="list-group" v-show="loaded === true">
            <a href="#" class="list-group-item" v-for="stop in stops" @click="setStop(stop)" :class="{'active': (selectedStop.id == stop.id)}">{{ stop.name }}</a>
            <small class="text-muted" v-show="(stops.length === 0 && loaded === true && busy === false)">...</small>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'stop-list',
        data() {
            return {
                loaded: false,
                busy: false,
                phrase: '',
                stops: [],
                selectedStop: {
                    id: null,
                    name: null
                },
                timer: null
            }
        },
        methods: {
            load() {
                this.search()                
                this.loaded = true
            },
            search() {                                
                this.busy = true
                this.stops = []
                
                if (this.timer) {
                    clearTimeout(this.timer)
                    this.timer = null
                }
                
                this.timer = setTimeout(() => {
                    let that = this
                    
                    axios
                        .get('/stops?phrase=' + this.phrase)
                        .then(response => {
                            that.busy = false
                            that.stops = response.data
                        })
                }, 250)
            },
            setStop(stop) {
                if (this.selectedStop.id === stop.id) {
                    let selectedStop = {
                        id: null,
                        name: null
                    }
                    this.selectedStop = selectedStop
                    this.$store.dispatch('setStop', selectedStop)
                } else {
                    this.selectedStop = stop
                    this.$store.dispatch('setStop', stop)
                }
            }
        },
        mounted() {
            this.load()
        }
    }
</script>