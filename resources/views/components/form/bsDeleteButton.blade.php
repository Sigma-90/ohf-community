{{ Form::button( (isset($icon) ? '<i class="fa fa-' . $icon. '"></i> ' : '') . $label, [ 'type' => 'submit', 'class' => 'btn btn-danger delete-confirmation', 'data-confirmation' => $confirmation ] ) }}