import axios from 'axios'
import * as types from './types'

const store = {
  /**
   * State
   * @type {Object}
   */
  state: {
    members: [],
    companies: [],
    schedules: [],
  },

  /**
   * [getters description]
   * @type {Object}
   */
  getters: {
    members: state => state.members,
    properties: state => state.properties,
    companies: state => state.companies,
    schedules: state => state.schedules,
  },

  /**
   * Mutations
   * @type {Object}
   */
  mutations: {
    [types.MEMBER_LIST] (state, members) {
      state.members = members
    },
    [types.COMPANY_LIST] (state, companies) {
      state.companies = companies
    },
    [types.SCHEDULE_LIST] (state, schedules) {
      state.schedules = schedules
    },
    [types.PROPERTY_LIST] (state, properties) {
      state.properties = properties
    }
  },

  /**
   * Actions
   * @type {Object}
   */
  actions: {
    getMembers ({ commit }, { id} = {}) {
      return axios.get('/api/team-members/'+id, {
      }).then(response => {
        commit(types.MEMBER_LIST, response.data)
        return response.data
      })
    },
    getCompany ({ commit }, { id} = {}) {
      return axios.get('/api/companies', {
      }).then(response => {
        commit(types.COMPANY_LIST, response.data)
        return response.data
      })
    },
    getProperty ({ commit }, { id} = {}) {
      return axios.get('/api/property/lists/'+id, {
      }).then(response => {
        commit(types.PROPERTY_LIST, response.data)
        return response.data
      })
    },
    getSchedule ({ commit }, { empid, week} = {}) {
      return axios.get('/api/week-schedule/'+empid+'/'+week, {
      }).then(response => {
        commit(types.SCHEDULE_LIST, response.data)
        return response.data
      })
    },

    getScheduleByMonth ({ commit }, { empid, month} = {}) {
      return axios.get('/api/month-schedule/'+empid+'/'+month, {
      }).then(response => {
        commit(types.SCHEDULE_LIST, response.data)
        return response.data
      })
    }
  }
}

export default store
