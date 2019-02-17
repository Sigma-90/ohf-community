<nav class="navbar navbar-expand-lg navbar-dark bg-dark justify-content-between row m-0 px-0">

    <div class="col-auto d-block d-md-none pr-1 pr-sm-3">
        @if( isset($buttons['back']) && $buttons['back']['authorized'] )
            {{-- Back button --}}
            <a href="{{ $buttons['back']['url'] }}" class="btn btn-link text-light">
                @icon(arrow-left)
            </a>
        @else
            {{-- Sidebar navigation toggle --}}
            <a href="javascript:;" class="toggle-nav btn btn-link text-light toggle-button">
                @icon(navicon)
            </a>
        @endif
    </div>

    <a href="javascript:;" class="toggle-nav btn btn-link text-light toggle-button d-none d-md-inline-block ml-3">
        @icon(navicon)
    </a>

    {{-- Brand --}}
    <div class="col-auto px-0 px-sm-3">

        {{-- Logo, Name --}}
        <a class="navbar-brand d-none d-md-inline-block" href="{{ route('home') }}">
            <img src="{{ asset('/img/logo.png') }}" /> {{ Config::get('app.name') }}
        </a>
        {{-- Title --}}
        @if(View::hasSection('title'))
            <span class="text-light ml-md-4">@yield('title')</span>
        @endif

    </div>

    {{-- Right side --}}
    <div class="col text-right">

        {{-- Buttons --}}
        @if ( isset( $buttons ) && sizeof($buttons) > 0 )
            @foreach( $buttons as $key => $button )
                @if ( $button['authorized'] )
                    @if( $key == 'delete' && isset($button['confirmation']) )
                        <form method="POST" action="{{ $button['url'] }}" class="d-inline">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            {{ Form::button('<i class="fa fa-' . $button['icon'] .'"></i> ' . $button['caption'], [ 'type' => 'submit', 'class' => 'btn btn-danger d-none d-md-inline-block delete-confirmation', 'data-confirmation' => $button['confirmation'] ]) }}
                            {{ Form::button('<i class="fa fa-' . $button['icon'] .'"></i>', [ 'type' => 'submit', 'class' => 'btn btn-link text-light d-md-none delete-confirmation', 'data-confirmation' => $button['confirmation'] ]) }}
                        </form>
                    @elseif( $key == 'action' )
                        <a href="{{ $button['url'] }}" class="btn btn-primary d-none d-md-inline-block">
                            @icon({{ $button['icon'] }}) {{ $button['caption'] }}
                        </a>
                    @elseif( $key == 'back' )
                        <a href="{{ $button['url'] }}" class="btn btn-secondary d-none d-md-inline-block">
                            @icon({{ $button['icon'] }}) {{ $button['caption'] }}
                        </a>
                    @else
                        <a href="{{ $button['url'] }}" class="btn btn-secondary d-none d-md-inline-block">
                            @icon({{ $button['icon'] }}) {{ $button['caption'] }}
                        </a>
                        <a href="{{ $button['url'] }}" class="btn text-light d-md-none" title="{{ $button['caption'] }}">
                            @icon({{ $button['icon'] }})
                        </a>
                    @endif
                @endif
            @endforeach
        @endif

        {{-- Context menu --}}
        @if ( isset( $menu ) && sizeof($menu) > 0 )
            @component('components.context-nav')
                @foreach( $menu as $item )
                    @if ( $item['authorized'] )
                        <li>
                            <a href="{{ $item['url'] }}" class="btn btn-light btn-block">
                                @icon({{ $item['icon'] }} mr-1) {{ $item['caption'] }}
                            </a>
                        </li>
                    @endif
                @endforeach
            @endcomponent
        @endif

    </div>

</nav>
