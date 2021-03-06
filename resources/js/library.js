import Vue from 'vue'

import FontAwesomeIcon from '@/components/common/FontAwesomeIcon'
Vue.component('font-awesome-icon', FontAwesomeIcon)

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue)

import LendingPage from '@/pages/library/LendingPage'
import LendingPersonPage from '@/pages/library/LendingPersonPage'
import BooksPage from '@/pages/library/BooksPage'
import BookRegisterPage from '@/pages/library/BookRegisterPage'
import BookEditPage from '@/pages/library/BookEditPage'
import LendingBookPage from '@/pages/library/LendingBookPage'

import i18n from '@/plugins/i18n'

import './plugins/vee-validate'

import ziggyMixin from '@/mixins/ziggyMixin'
Vue.mixin(ziggyMixin)

Vue.config.productionTip = false

new Vue({
    el: '#library-app',
    i18n,
    components: {
        LendingPage,
        LendingPersonPage,
        BooksPage,
        BookRegisterPage,
        BookEditPage,
        LendingBookPage
    }
})
