<template>
    <div id="stops">
        <div class="spinner" v-show="loaded === false">
            <i class="fas fa-sync fa-spin"></i>
        </div>
        <form>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Search..." v-model="phrase" @keyup="search" :disabled="loaded === false">
            </div>
        </form>
        <div class="list-group" v-show="loaded === true">
            <a href="#" class="list-group-item" v-for="stop in stops">{{ stop.name }}</a>
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
            }
        },
        mounted() {
            this.load()
        }
    }
</script>