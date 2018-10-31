<div class="row">
    @foreach($data as $item)
        <div class="col-lg-2 col-md-3 col-sm-4 col-6">
            <div class="card mb-4">
                <div class="card-header p-2">
                    @if($item['model']->person->gender == 'f')@icon(female) 
                    @elseif($item['model']->person->gender == 'm')@icon(male) 
                    @endif
                    {{ $item['model']->person->fullName }}
                </div>
                <div class="card-body p-0">
                    @can('view', $item['model'])
                        <a href="{{ route('people.helpers.show', $item['model']) }}">
                    @endcan
                    @isset($item['model']->person->portrait_picture)
                        <img src="{{ Storage::url($item['model']->person->portrait_picture) }}" class="img-fluid">
                    @else
                        <img src="{{ asset('img/portrait_placeholder.png') }}" class="img-fluid">
                    @endisset
                    @can('view', $item['model'])
                        </a>
                    @endcan
                </div>
                <div class="card-body py-1 px-2">
                    <small>
                        {{ $item['model']->person->age }}@if($item['model']->person->age != null && $item['model']->person->nationality != null),@endif
                        {{ $item['model']->person->nationality }}
                    </small>
                </div>
            </div>
        </div>
    @endforeach
</div>
