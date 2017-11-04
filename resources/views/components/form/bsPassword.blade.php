@component('components.form.bsInput', [ 'name' => $name, 'label' => $label, 'help' => $help ])
    {{ Form::password($name, array_merge([ 'class' => 'form-control'.($errors->has($name) ? ' is-invalid' : ''), 'autocomplete' => 'new-password' ], $attributes)) }}
@endcomponent