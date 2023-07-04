import { createApp } from 'vue'
import { router } from './router'
import { store } from './store'
import App from './App.vue'
import './main.css'

const app = createApp(App)

app.directive('clickAnyWhere', {
    beforeMount: (el, binding, vnode) => {
        el.clickAnyWhere = (e) => {
            if (!(el == e.target || el.contains(e.target))) {
                binding.value()
            }
        }
        document.body.addEventListener('click', el.clickAnyWhere)
    },
    unmounted: (el) => {
        document.body.removeEventListener('click', el.clickAnyWhere)
    }
})

app.use(router).use(store).mount('#app')
