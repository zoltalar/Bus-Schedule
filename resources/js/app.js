require('./bootstrap')

window.Vue = require('vue')
window.axios = require('axios')

Vue.component('app', require('./components/app'))
Vue.component('stop', require('./components/stop'))
Vue.component('stop-list', require('./components/stop-list'))
Vue.component('stop-route', require('./components/stop-route'))
Vue.component('timer', require('./components/timer'))

import store from './store'

const app = new Vue({
    el: '#root',
    store,
    template: '<app></app>'
})