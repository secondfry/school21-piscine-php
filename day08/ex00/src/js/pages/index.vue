<script>
import LayoutDefault from '../layouts/default.vue'
import Lobby from '../components/lobby.vue';

export default {
  components: {
    LayoutDefault,
    Lobby
  }
}
</script>

<template>
  <layout-default>
    <p>Index</p>
    <lobby></lobby>
  </layout-default>
</template>
