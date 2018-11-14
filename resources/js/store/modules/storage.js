export default {
    state: {
        stop: {
            id: null,
            name: null
        }
    },
    mutations: {
        setStop(state, stop) {
            state.stop = stop
        }
    },
    getters: {
        stop: state => state.stop
    },
    actions: {
        setStop({ commit }, data) {
            commit('setStop', data)
        }
    }
}