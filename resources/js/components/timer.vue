<template>
    <span class="badge badge-primary">{{ time }}</span>
</template>
<script>
    export default {
        name: 'timer',
        data() {
            return {
                time: ''
            }
        },
        methods: {
            read() {
                let date = new Date()
                let hours = date.getHours()
                let minutes = date.getMinutes()
                let seconds = date.getSeconds()
                
                if (hours < 10) {
                    hours = '0' + hours
                }
                
                if (minutes < 10) {
                    minutes = '0' + minutes
                }
                
                if (seconds < 10) {
                    seconds = '0' + seconds
                }
                
                return [hours, minutes, seconds].join(':')
            },
            tick() {
                setInterval(() => {
                    this.time = this.read()
                }, 1000)
            }
        },
        created() {
            this.tick()
        }
    }
</script>