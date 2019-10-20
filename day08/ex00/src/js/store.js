import Vuex from 'vuex'

import user from './state/user'

const store = new Vuex.Store({
  modules: {
    user,
  }
});

export default store;
