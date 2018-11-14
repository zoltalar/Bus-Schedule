<template>
    <div id="stop">
        <h5>{{ name }}</h5>
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
        created() {
            this.$store.watch(
                (state) => {
                    return this.$store.state.stop
                },
                (newValue, oldValue)=>{
                  console.log(oldValue)
                  console.log(newValue)
                }
            )
        }
    }
</script>