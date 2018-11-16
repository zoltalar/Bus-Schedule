<template>
    <div id="stop">
        <h5 class="mb-4">{{ name }}</h5>
        <table class="table" v-show="schedules.length > 0">
            <thead>
                <tr>
                    <th>Vehicle</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="schedule in schedules">
                    <td width="20%">{{ schedule.vehicle.name }}</td>
                    <td>{{ schedule.time }}</td>
                </tr>
            </tbody>
        </table>
        <p v-show="selectedStop.id !== null && schedules.length == 0">No schedules to show in next hour.</p>
    </div>
</template>
<script>
    export default {
        name: 'stop',
        data() {
            return {
                name: '',
                schedules: []
            }
        },
        methods: {
            loadSchedules(id) {
                if (id === null) {
                    this.schedules = []
                } else {
                    axios
                        .get('/schedules/closest/' + id)
                        .then(response => {
                            this.schedules = response.data
                        })
                }
            }
        },
        computed: {
            selectedStop() {
                return this.$store.getters.stop
            }
        },
        watch: {
            'selectedStop.id': function(newVal, oldVal) {
                this.loadSchedules(newVal)
            },
            'selectedStop.name': function(newVal, oldVal) {
                this.name = newVal
            }
        }
    }
</script>