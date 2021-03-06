<template>
    <div>
        <person-filter-input
            :value="filter"
            :busy="busy"
            :disabled="disableSearch"
            :placeholder="$t('people.bank_search_text')"
            @submit="search"
            @reset="reset"
        />
        <div
            v-if="loaded"
            class="mt-3"
        >
            <template v-if="totalRows > 0">
                <bank-person-card
                    v-for="person in persons"
                    :key="person.id"
                    :person="person"
                    :highlight-terms="searchTerms"
                    :disabled="disabledCards.indexOf(person.id) >= 0"
                    @change="reloadPerson(person)"
                />
                <div class="row align-items-center">
                    <div class="col">
                        <b-pagination
                            v-if="totalRows > 0 && perPage > 0 && totalRows > perPage"
                            v-model="currentPage"
                            size="sm"
                            :total-rows="totalRows"
                            :per-page="perPage"
                            class="mb-0"
                        />
                    </div>
                    <div class="col-auto">
                        <small>{{ totalRows }} total results</small>
                    </div>
                </div>
            </template>
            <template v-else>
                <info-alert
                    v-if="message"
                    :message="message"
                />
                <info-alert
                    v-else
                    :message="$t('app.not_found')"
                />
                <register-person-button
                    v-if="canRegisterPerson != null"
                    :url="registerPersonUrlWithQuery"
                />
            </template>
        </div>
        <bank-stats
            v-else-if="filter.length == 0"
        />
    </div>
</template>

<script>

import SessionVariable from '@/sessionVariable'
const rememberedFilter = new SessionVariable('bank.withdrawal.filter')

import { handleAjaxError } from '@/utils'

import PersonFilterInput from '@/components/people/PersonFilterInput'
import BankPersonCard from '@/components/bank/BankPersonCard'
import RegisterPersonButton from '@/components/people/RegisterPersonButton'
import BankStats from '@/components/bank/BankStats'

import InfoAlert from '@/components/alerts/InfoAlert'
import { BPagination } from 'bootstrap-vue'

import { EventBus } from '@/event-bus';

import bankApi from '@/api/bank'
export default {
    components: {
        PersonFilterInput,
        BankPersonCard,
        RegisterPersonButton,
        BankStats,
        InfoAlert,
        BPagination
    },
    props: {
        canRegisterPerson: Boolean,
        registerPersonUrl: {
            type: String,
            required: false,
            default: null
        }
    },
    data() {
        return {
            persons: [],
            loaded: false,
            totalRows: 0,
            registerQuery: '',
            perPage: 0,
            currentPage: 1,
            filter: rememberedFilter.get(''),
            busy: false,
            message: null,
            searchTerms: [],
            disabledCards: [],
            disableSearch: false
        }
    },
    computed: {
        registerPersonUrlWithQuery() {
            let str = this.registerPersonUrl
            if (this.registerQuery.length > 0) {
                str += `?${this.registerQuery}`
            }
            return str
        }
    },
    watch: {
        currentPage() {
            this.search(this.filter)
        }
    },
    mounted() {
        if (this.filter.length > 0) {
            this.search(this.filter)
        }
    },
    methods: {
        search(filter) {
            this.busy = true
            this.message = null
            this.searchTerms = []
            rememberedFilter.forget()
            if (this.filter != filter) {
                this.currentPage = 1
            }
            bankApi.searchPersons(filter, this.currentPage)
                .then((data) => {
                    this.persons = data.data
                    this.totalRows = data.meta.total
                    this.perPage = data.meta.per_page
                    this.registerQuery = data.meta.register_query
                    this.loaded = true
                    this.filter = filter
                    if (this.persons.length > 0) {
                        rememberedFilter.set(filter)
                    }
                    if (data.meta.terms.length > 0) {
                        this.searchTerms = data.meta.terms
                    }
                })
                .catch(err => handleAjaxError(err))
                .then(() => this.busy = false)
                .then(() => {
                    if (this.totalRows == 0) {
                        EventBus.$emit('zero-results');
                    }
                })
        },
        reloadPerson(person) {
            if (person.url) {
                this.disableSearch = true
                this.disabledCards.push(person.id)
                bankApi.findPerson(person.id)
                    .then((data) => {
                        if (data.data) {
                            const idx = this.persons.findIndex(p => p.id == person.id)
                            this.persons[idx] = data.data
                        }
                        this.disabledCards.splice(this.disabledCards.indexOf(person.id))
                    })
                    .catch(err => handleAjaxError(err))
                    .then(() => this.disableSearch = false)
            }
        },
        reset() {
            this.persons = []
            this.totalRows = 0
            this.loaded = false
            this.message = null
            rememberedFilter.forget()
            this.filter = ''
        }
    }
}
</script>
