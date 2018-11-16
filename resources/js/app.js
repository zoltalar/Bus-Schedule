require('./bootstrap')

window.Vue = require('vue')
window.axios = require('axios')

Vue.component('app', require('./components/app'))
Vue.component('stop', require('./components/stop'))
Vue.component('stop-list', require('./components/stop-list'))
Vue.component('timer', require('./components/timer'))

Vue.filter('capitalize', function(value) {
    if ( ! value) return ''    
    value = value.toString()    
    return value.charAt(0).toUpperCase() + value.slice(1)
})

import store from './store'

const app = new Vue({
    el: '#root',
    store,
    template: '<app></app>'
})