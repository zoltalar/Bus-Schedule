import Vue from 'vue'
import Vuex from 'vuex'

import storage from '../store/modules/storage'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        storage
    }
})