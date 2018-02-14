import axios from 'axios'
import * as types from './types'

const store = {
  /**
   * State
   * @type {Object}
   */
  state: {
    user: {},
    errors: false,
  },

  /**
   * [getters description]
   * @type {Object}
   */
  getters: {
    appError: state => state.errors,
    user: state => state.user
  },

  /**
   * Mutations
   * @type {Object}
   */
  mutations: {
    [types.SET_APP_ERROR] (state, errors) {
      state.errors = errors
    },
    [types.SET_USER] (state, user) {
      state.user = user
    },
    [types.SET_USER_AVATAR] (state, avatar) {
      state.user.avatar = avatar
    },
    [types.SET_CURRENT_UNIT] (state, unit) {
      state.unit = unit
    }
  },

  /**
   * Actions
   * @type {Object}
   */
  actions: {
    closeModal ({ commit }) {
      document.body.classList.remove('no-scroll')

      commit(types.CLOSE_MODAL)
    },
    setUser ({ commit }, user) {
      commit(types.SET_USER, user)
    },
    setUserAvatar ({ commit }, avatar) {
      return axios.post('/update-avatar', { avatar }).then(response => {
        commit(types.SET_USER_AVATAR, response.data)

        return response.data
      })
    },
    setAppError ({ commit }, errors) {
      commit(types.SET_APP_ERROR, errors)
    }
  }
}

export default store
