require('./bootstrap');

window.Vue = require('vue')
window.axios = require('axios')

Vue.component('App', require('./components/App'))
Vue.component('StopList', require('./components/StopList'))

const app = new Vue({
    el: '#root',
    template: '<app></app>'
})