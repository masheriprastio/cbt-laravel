import { createApp } from 'vue';
import App from './App.vue';
import router from './router';

// Import global bootstrap utilities
import './bootstrap';

// Import TinyMCE initializer so it's bundled and available as window.initTiny
import './tinymce-init';
// Import Quill initializer so it's bundled and available as window.initQuill
import './quill-init';

const app = createApp(App);
app.use(router);
app.mount('#app');
