import './bootstrap'
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'

// mapeia todos os arquivos .vue dentro de Pages e subpastas
const pages = import.meta.glob('./Pages/**/*.vue')

createInertiaApp({
  resolve: name => {
    const page = pages[`./Pages/${name}.vue`]
    if (!page) {
      throw new Error(`Página não encontrada: ./Pages/${name}.vue`)
    }
    return page()
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .mount(el)
  },
})
