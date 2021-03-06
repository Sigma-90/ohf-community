<template>
    <div>

        <!-- Error -->
        <table-alert
            :value="errorText"
            @retry="refresh"
        />

        <slot name="top"></slot>

        <!-- Filter  -->
        <b-form-row>
            <b-col v-if="!!$slots['filter-prepend']" cols="auto">
                <slot name="filter-prepend"></slot>
            </b-col>
            <b-col>
                <table-filter
                    v-if="!noFilter"
                    v-model="filter"
                    :placeholder="filterPlaceholder"
                    :is-busy="isBusy"
                    :total-rows="totalRows"
                />
            </b-col>
            <b-col v-if="!!$slots['filter-append']" cols="auto">
                <slot name="filter-append"></slot>
            </b-col>
        </b-form-row>

        <!-- Table -->
        <b-table
            :id="id"
            striped
            hover
            small
            bordered
            responsive
            :items="itemProvider"
            :fields="fields"
            :primary-key="'id'"
            :busy.sync="isBusy"
            :sort-by.sync="sortBy"
            :sort-desc.sync="sortDesc"
            :per-page="perPage"
            :current-page="currentPage"
            :show-empty="true"
            :empty-text="emptyText"
            :empty-filtered-text="$t('app.no_records_matching_your_request')"
            :no-sort-reset="true"
            :filter="filter"
        >
            <template v-for="(_, slot) of $scopedSlots" v-slot:[slot]="scope"><slot :name="slot" v-bind="scope"/></template>
            <div slot="table-busy" class="text-center my-2">
                <b-spinner class="align-middle"></b-spinner>
                <strong>{{ loadingLabel }}</strong>
            </div>
            <template slot="empty" slot-scope="scope">
                <em>{{ scope.emptyText }}</em>
            </template>
            <template slot="emptyfiltered" slot-scope="scope">
                <em>{{ scope.emptyFilteredText }}</em>
            </template>
        </b-table>

        <!-- Pagination -->
        <table-pagination
            v-model="currentPage"
            :total-rows="totalRows"
            :per-page="perPage"
            :disabled="isBusy"
        />

    </div>
</template>

<script>
import TableAlert from '@/components/table/TableAlert'
import TableFilter from '@/components/table/TableFilter'
import TablePagination from '@/components/table/TablePagination'
export default {
    components: {
        TableAlert,
        TableFilter,
        TablePagination
    },
    props: {
        id: {
            required: true,
            type: String
        },
        fields: {
            required: false,
            type: Array
        },
        apiMethod: {
            required: true,
            type: Function
        },
        defaultSortBy: {
            required: true,
            type: String
        },
        defaultSortDesc: {
            required: false,
            type: Boolean,
            default: false
        },
        emptyText: {
            required: false,
            type: String
        },
        itemsPerPage: {
            required: false,
            type: Number,
            default: 25
        },
        filterPlaceholder: {
            require: false,
            type: String,
            default: function() {
                return this.$t('app.type_to_search')
            }
        },
        loadingLabel: {
            type: String,
            required: false,
            default: function() {
                return this.$t('app.loading')
            }
        },
        noFilter: Boolean
    },
    data() {
        return {
            isBusy: false,
            sortBy: sessionStorage.getItem(this.id + '.sortBy')
                ? sessionStorage.getItem(this.id + '.sortBy')
                : this.defaultSortBy,
            sortDesc: sessionStorage.getItem(this.id + '.sortDesc')
                ? sessionStorage.getItem(this.id + '.sortDesc') == 'true'
                : this.defaultSortDesc,
            currentPage: sessionStorage.getItem(this.id + '.currentPage')
                ? parseInt(sessionStorage.getItem(this.id + '.currentPage'))
                : 1,
            perPage: this.itemsPerPage,
            totalRows: 0,
            errorText: null,
            filter: sessionStorage.getItem(this.id + '.filter')
                ? sessionStorage.getItem(this.id + '.filter')
                : ''
        }
    },
    watch: {
        filter () {
            this.currentPage = 1
        }
    },
    methods: {
        async itemProvider (ctx) {
            this.errorText = null
            const params = {
                filter: ctx.filter,
                page: ctx.currentPage,
                pageSize: ctx.perPage,
                sortBy: ctx.sortBy,
                sortDirection: ctx.sortDesc ? 'desc' : 'asc'
            }
            try {
                let data = await this.apiMethod(params)
                this.totalRows = data.meta.total
                sessionStorage.setItem(this.id + '.sortBy', ctx.sortBy)
                sessionStorage.setItem(this.id + '.sortDesc', ctx.sortDesc)
                sessionStorage.setItem(this.id + '.currentPage', ctx.currentPage)
                if (ctx.filter.length > 0) {
                    sessionStorage.setItem(this.id + '.filter', ctx.filter)
                } else {
                    sessionStorage.removeItem(this.id + '.filter')
                }
                return data.data || []
            } catch (err) {
                this.errorText = err
                this.totalRows = 0
                sessionStorage.removeItem(this.id + '.sortBy')
                sessionStorage.removeItem(this.id + '.sortDesc')
                sessionStorage.removeItem(this.id + '.currentPage')
                sessionStorage.removeItem(this.id + '.filter')
                return []
            }
        },
        refresh () {
            this.$root.$emit('bv::refresh::table', this.id)
        }
    }
}
</script>
