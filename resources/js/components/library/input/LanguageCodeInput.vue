<template>
    <validation-provider
        :name="label"
        :vid="vid"
        :rules="rules"
        v-slot="validationContext"
    >
        <b-form-group
            :label="!hideLabel ? label : null"
            :state="getValidationState(validationContext)"
            :invalid-feedback="validationContext.errors[0]"
        >
            <b-form-select
                v-model="modelValue"
                ref="input"
                :options="languages"
                :state="getValidationState(validationContext)"
                :disabled="disabled"
            />
        </b-form-group>
    </validation-provider>
</template>

<script>
import commonApi from '@/api/common'
import formInputMixin from '@/mixins/formInputMixin'
export default {
    mixins: [formInputMixin],
    props: {
        value: {
            type: String,
        },
        hideLabel: Boolean
    },
    data () {
        return {
            label: this.$t('app.language'),
            vid: 'language_code',
            rules: {},
            languages: [{
                value: null,
                text: this.$t('app.choose_language')
            }]
        }
    },
    created () {
        this.loadLanguages()
    },
    methods: {
        loadLanguages () {
            commonApi.listLanguages()
                .then(data => {
                    const languages = Object.entries(data)
                        .map(e => {
                            return {
                                value: e[0],
                                text: e[1]
                            }
                        })
                    this.languages.push(...languages)
                })
        }
    }
}
</script>
