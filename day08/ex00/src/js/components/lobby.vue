<script>
import Chat from './chat.vue';
import GameList from './game-list.vue';

export default {
  components: {
    Chat,
    GameList
  }
}
</script>

<template>
  <div>
    <h1>Welcome to the lobby</h1>
    <game-list></game-list>
    <chat></chat>
  </div>
</template>
