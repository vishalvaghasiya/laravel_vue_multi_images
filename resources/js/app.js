require('./bootstrap');
window.Vue = require('vue').default;
import App from "./components/App";
import Routes from './routes';
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const routeConst = new VueRouter({
    routes: Routes,
    mode: 'history',
})

const app = new Vue({
    el: '#app',
    components: {
        'srd': App
    },
    router: routeConst,
});
