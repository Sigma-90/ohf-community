import Vue from 'vue'

import FontAwesomeIcon from '@/components/common/FontAwesomeIcon'
Vue.component('font-awesome-icon', FontAwesomeIcon)

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue)

import DonorsTable from '@/components/fundraising/DonorsTable'
import DonationsTable from '@/components/fundraising/DonationsTable'
import DonorsReport from '@/components/fundraising/DonorsReport'
import DonorShowPage from '@/pages/fundraising/DonorShowPage'

import i18n from '@/plugins/i18n'

import './plugins/vee-validate'

import ziggyMixin from '@/mixins/ziggyMixin'
Vue.mixin(ziggyMixin)

Vue.config.productionTip = false

new Vue({
    el: '#fundraising-app',
    i18n,
    components: {
        DonationsTable,
        DonorsTable,
        DonorsReport,
        DonorShowPage
    }
});
