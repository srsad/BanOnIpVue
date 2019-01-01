import Vue from 'vue'
import App from './App.vue'
import axios from 'axios'
import VueAxios from 'vue-axios'
import VModal from 'vue-js-modal'
import VueNoty from 'vuejs-noty'

import vueModx from 'vuemodx'
import Grid from './components/Grid.vue'
import WindowItem from './components/Window.vue'

import './assets/main.sass'
import 'vuejs-noty/dist/vuejs-noty.css'

Vue.config.productionTip = false

export const bus = new Vue();

window.onload = function() {
	Vue.use(VueAxios, axios)
	Vue.use(VModal)
	Vue.use(VueNoty)

	Vue.use(Grid)
	Vue.use(WindowItem)
	Vue.use(vueModx, {
		lexicon: MODx.lang,
		appId: 'banonip-panel-home-div'
	})

	Vue.component('ip-grid', Grid)
	Vue.component('window-item', WindowItem)

	Vue.prototype.$httpConfig = {headers: {'modAuth': BanOnIP.modAuth}};

	new Vue({
		render: h => h(App),
	}).$mount('#banonip-panel-home-div');
};
