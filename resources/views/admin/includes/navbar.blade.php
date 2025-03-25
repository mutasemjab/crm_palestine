<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        @if (auth()->user()->is_constructor == 1)
        @else
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
          </li>
        @endif

      <li class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">{{__('messages.Home')}}</a>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.logout') }}" class="nav-link">{{__('messages.Logout')}}</a>
      </li>
        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
        <a class="nav-link"  hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
            {{ $properties['native'] }}
        </a>
    @endforeach
    </ul>


  </nav>
