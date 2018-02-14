import Vue from 'vue'
import Vuex from 'vuex'
import app from './app'
import modal from './modal'
import membership from './membership'

import createLogger from 'vuex/dist/logger'

const debug = process.env.NODE_ENV !== 'production'

export default new Vuex.Store({
  modules: {
    app,
    modal,
    membership
  },
  strict: debug,
  plugins: debug ? [createLogger()] : []
})
