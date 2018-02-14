import axios from 'axios'
import * as types from './types'

const store = {
  /**
   * State
   * @type {Object}
   */
  state: {
    activeModal: false,
    confirmModal: {
      options: null,
      opened: false,
      resolve: null,
      reject: null
    },
    modalParams: false
  },

  /**
   * [getters description]
   * @type {Object}
   */
  getters: {
    modalParams: state => state.modalParams,
    confirmModal: state => state.confirmModal,
    activeModal: state => state.activeModal
  },

  /**
   * Mutations
   * @type {Object}
   */
  mutations: {
    [types.SET_ACTIVE_MODAL] (state, modal) {
      state.activeModal = modal
    },
    [types.SET_MODAL_PARAMETER] (state, parameters) {
      state.modalParams = parameters
    },
    [types.CLOSE_MODAL] (state, modal) {
      state.activeModal = false
      state.modalParams = false
    },
    [types.SET_CONFIRM_STATUS] (state, status) {
      state.confirmModal.opened = status
    },
    [types.SET_CONFIRM_PROMISE] (state, { resolve, reject }) {
      state.confirmModal.resolve = resolve
      state.confirmModal.reject = reject
    },
    [types.SET_CONFIRM_OPTIONS] (state, options) {
      state.confirmModal.options = options
    }
  },

  /**
   * Actions
   * @type {Object}
   */
  actions: {
    openModal ({ commit }, modal) {
      document.body.classList.add('no-scroll')

      if (typeof modal === 'object') {
        commit(types.SET_MODAL_PARAMETER, modal.params)
        modal = modal.name
      }

      commit(types.SET_ACTIVE_MODAL, modal)
    },
    closeModal ({ commit }) {
      document.body.classList.remove('no-scroll')

      commit(types.CLOSE_MODAL)
    },
    confirmAction ({ commit, state }) {
      state.confirmModal.resolve()
      commit(types.SET_CONFIRM_STATUS, false)
    },
    confirm ({ commit, state }, options = {}) {
      commit(types.SET_CONFIRM_OPTIONS, options)
      commit(types.SET_CONFIRM_STATUS, true)

      return new Promise((resolve, reject) => {
        commit(types.SET_CONFIRM_PROMISE, { resolve, reject })
      })
    },
    setConfirmModalStatus ({ commit }, status) {
      commit(types.SET_CONFIRM_STATUS, status)
    }
  }
}

export default store
