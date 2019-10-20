const events = {
  TRACK: 'USER_TRACK',
  TRACK_CACHE_HIT: 'TRACK_CACHE_HIT',
  SETTINGS_SET_NIGHT: 'SETTINGS_SET_NIGHT',
  SETTINGS_CACHE_HIT: 'SETTINGS_CACHE_HIT',
};

export default {
  state: {
    user: {
      id: null
    },
    settings: {
      darkMode: false,
    }
  },
  getters: {
    hasUser: state => !!state.user.id
  },
  mutations: {
    [events.TRACK_CACHE_HIT] (state, id) {
      state.user.id = id;
    },
    [events.TRACK] (state, id) {
      state.user.id = id;
    },
    [events.SETTINGS_SET_NIGHT] (state, isDark) {
      state.settings.darkMode = isDark;
    },
    [events.SETTINGS_CACHE_HIT] (state, data) {
      state.settings.darkMode = data.isDark;
    },
  },
  actions: {
    async loadUserId ({ commit }) {
      const data = await this._vm.$getItem('user');
      if (!data) {
        return;
      }

      commit(events.TRACK_CACHE_HIT, data.id);
    },
    async trackUserId ({ commit }, id) {
      await this._vm.$setItem('user', { id });
      return commit(events.TRACK, id);
    },
    async loadSettings ({ commit }) {
      const data = await this._vm.$getItem('settings');
      if (!data) {
        return;
      }

      commit(events.SETTINGS_CACHE_HIT, data);
    },
    async setNight ({ commit }, isDark) {
      localStorage.isDark = isDark;
      document.body.style.background = '';

      await this._vm.$setItem('settings', { isDark });
      return commit(events.SETTINGS_SET_NIGHT, isDark);
    }
  }
}
