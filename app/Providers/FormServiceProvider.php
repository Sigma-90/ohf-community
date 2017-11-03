<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Collective\Html\FormFacade as Form;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Form::component('bsText', 'components.form.bsText', [ 'name', 'value' => null, 'attributes' => [], 'label' => null, 'help' => null ]);
        Form::component('bsNumber', 'components.form.bsNumber', [ 'name', 'value' => null, 'attributes' => [], 'label' => null, 'help' => null ]);
        Form::component('bsPassword', 'components.form.bsPassword', [ 'name', 'attributes' => [], 'label' => null, 'help' => null ]);
        Form::component('bsCheckbox', 'components.form.bsCheckbox', [ 'name', 'value' => null, 'checked' => null, 'label' => null ]);
        Form::component('bsSubmitButton', 'components.form.bsSubmitButton', [ 'label', 'icon' => 'check' ]);
        Form::component('bsDeleteButton', 'components.form.bsDeleteButton', [ 'label' => 'Delete', 'icon' => 'trash', 'confirmation' => 'Do you really want to delete this item?' ]);
        Form::component('bsDeleteForm', 'components.form.bsDeleteForm', [ 'action', 'label' => 'Delete', 'icon' => 'trash', 'confirmation' => 'Do you really want to delete this item?' ]);
        Form::component('bsButtonLink', 'components.form.bsButtonLink', [ 'href', 'label', 'icon', 'class' => 'secondary' ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
