{{-- Navigation --}}
<ul class="nav flex-column nav-pills my-3 mt-0">
    @foreach ($nav as $n)
        @if ($n['authorized'])
            <li class="nav-item">
                <a class="nav-link {{ Request::is($n['active']) ? 'active' : '' }}" href="{{ route($n['route']) }}">
                    <i class="fa fa-{{ $n['icon'] }}" title="{{ $n['caption'] }}"></i> {{ $n['caption'] }}
                    @if ($n['route'] == 'tasks.index' and $num_open_tasks > 0)
                        <span class="badge badge-secondary">{{ $num_open_tasks }}</span>
                    @endif
                </a>
            </li>
        @endif
    @endforeach
</ul>

{{-- Footer --}}
<footer class="footer">

    <hr>
    <div class="text-center">
        <a href="{{ route('userprofile') }}"><h1 class="display-4">@icon(user)</h1></a>
        {{ Auth::user()->name }}
    </div>

    {{-- Logout --}}
    <div class="px-3 mt-3">
        <form class="form-inline" action="{{ route('logout') }}" method="POST">
            {{ csrf_field() }}
            <button type="submit" class="btn btn-block btn-secondary">@icon(sign-out) Logout</button>
        </form>
    </div>

    <hr>
    <p class="copyright text-muted px-3">
        <a href="{{ Config::get('app.product_url') }}" target="_blank" class="text-dark">{{ Config::get('app.product_name') }}</a><br>
        Version: {{ $app_version }}<br>
        &copy; Nicolas Perrenoud<br>
        Page rendered in {{ round((microtime(true) - LARAVEL_START)*1000) }} ms
    </p>
</footer>