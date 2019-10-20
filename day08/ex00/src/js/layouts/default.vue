<script>
import { mapActions, mapGetters, mapState } from 'vuex';

import Glyphicons from './power-threads-glyphicons.vue'

export default {
  computed: {
    ...mapState({
      darkMode: state => state.user.settings.darkMode,
    }),
    cssClassesRoot () {
      return this.darkMode ? 'bg-night text-light' : '';
    },
    switchText () {
      return this.darkMode ? 'Return to light' : 'Join the dark side';
    },
  },
  methods: {
    ...mapActions([
      'loadUserId',
      'loadSettings',
      'setNight',
    ]),
    ...mapGetters([
      'hasUser',
    ]),
    navigate (e) {
      e.preventDefault();
      this.$router.push({
        name: e.target.dataset.name
      });
    },
    reset (e) {
      e.preventDefault();
      return localforage.clear()
        .then(() => {
            location.reload();
          }
        )
    },
    track (e) {
      e.preventDefault();
      location.href = 'https://login.eveonline.com/oauth/authorize/?response_type=token&client_id=51c4a940a2464ea98df98c8f0dc1bf71&redirect_uri=https://wds-stats.secondfry.ru/track/';
    },
    switchSide (e) {
      e.preventDefault();
      this.setNight(!this.darkMode);
    },
  },
  created () {
    this.loadSettings();
    this.loadUserId();
  },
  components: {
  }
}
</script>

<template>
  <div id="root" :class="cssClassesRoot">
    <nav class="navbar navbar-dark bg-dark navbar-expand-lg">
      <div class="container">
        <a class="navbar-brand" href="/" data-name="root" @click="navigate">oadhesiv rush01</a>
        <ul class="navbar-nav w-100">
          <li class="nav-item">
            <a class="nav-item nav-link" href="/register" data-name="register" @click="navigate">Register</a>
          </li>
          <li class="nav-item mr-auto">
            <a class="nav-item nav-link" href="/login" data-name="login" @click="navigate">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-item nav-link" href="#" @click="switchSide" v-text="switchText"></a>
          </li>
          <li class="nav-item">
            <a class="nav-item nav-link" href="#" @click="reset">Reset</a>
          </li>
        </ul>
      </div>
    </nav>
    <div class="container my-3">
      <slot></slot>
    </div>
    <footer class="sf-footer container text-center">
      <p>
        Yours truly, <a href="https://github.com/secondfry">@Second_Fry</a>.
      </p>
    </footer>
  </div>
</template>

<style lang="scss">
  .sf-footer {
    padding-bottom: 80px;
  }
  .bg-night {
    background: $color-night !important;

    a {
      color: $color-night-a;
    }

    .bg-success {
      background: $color-night-success !important;
    }

    .table {
      color: $gray-100 !important;
    }
  }
</style>
