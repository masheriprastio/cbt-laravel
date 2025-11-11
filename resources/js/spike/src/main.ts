import { createApp } from 'vue'
import App from './App.vue'

// Router (dipisah ke file sendiri)
import router from './router'

// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

// SCSS global Spike
import './scss/style.scss'

const vuetify = createVuetify({ components, directives })

createApp(App)
  .use(router)
  .use(vuetify)
  .mount('#spike-app')
