require('./bootstrap')

window.Vue = require('vue')
window.axios = require('axios')

Vue.component('app', require('./components/app'))
Vue.component('stop-list', require('./components/stop-list'))

import store from './store'

const app = new Vue({
    el: '#root',
    store,
    template: '<app></app>'
})