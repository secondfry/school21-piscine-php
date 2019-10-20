import Vue from 'vue'
import Vuex from 'vuex'
import VueRouter from 'vue-router'
import VueLocalForage from 'vue-localforage'
import router from './router'
import store from './store'

Vue.use(Vuex);
Vue.use(VueLocalForage);
Vue.use(VueRouter);

const app = new Vue({
  router,
  store,
  el: '#vue-root',
});
