import Vue from 'vue'
import App from './App.vue'
import TorrentItem from './TorrentItem.vue'
import TorrentList from './TorrentList.vue'

window.axios = require('axios');

Vue.component('App', App);
Vue.component('TorrentItem', TorrentItem);
Vue.component('TorrentList', TorrentList);

new Vue({
  el: '#app'
});
