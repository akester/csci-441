require('./bootstrap');

import { createApp } from 'vue'
import Editor from './components/Editor.vue';

createApp({
    components: { Editor }
}).mount('#app')
