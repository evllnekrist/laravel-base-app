<!-- BEGIN: Main Menu-->
    <?php   
        if(!(Session::get('_user') && array_key_exists('_menu',Session::get('_user')))){
            header('Location: '.route('login'));
        }
    ?>
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mr-auto"><a class="navbar-brand" href="{{ url('home') }}">
                        <div class="brand-logo" style="background-position: center;background-size: contain;"></div>
                        <h2 class="brand-text mb-0">@lang('app.2nd_name')</h2>
                    </a></li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="feather icon-x d-block d-xl-none font-medium-4 primary toggle-icon"></i><i class="toggle-icon feather icon-disc font-medium-4 d-none d-xl-block collapse-toggle-icon primary" data-ticon="icon-disc"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">

            @foreach(Session::get('_user')['_menu'] as $key => $value)
            @if(is_array($value) || is_object($value))
                @if(!isset($value['detail']->name))
                    <li class="navigation-header"><span>{{ $value['title'] }}</span></li>
                    @foreach($value['detail'] as $key2 => $value2)
                        <li class="nav-item {{ (request()->segment(1) == url($value2->slug)) ? 'active' : '' }}">
                            <a href="{{ url($value2->slug) }}">
                                <i class="{{ $value2->icon }}"></i>
                                <span class="menu-title" data-i18n="{{ $value2->name }}">
                                    {{ $value2->name }}
                                </span>
                            </a>
                        </li>
                    @endforeach
                @else
                    <li class="navigation-header"><span></span></li>
                    <li class="nav-item {{ (request()->segment(1) == url($value['detail']->slug)) ? 'active' : '' }}">
                        <a href="{{ url($value['detail']->slug) }}">
                            <i class="{{ $value['detail']->icon }}"></i>
                            <span class="menu-title" data-i18n="{{ $value['detail']->name }}">
                                {{ $value['detail']->name }}
                            </span>
                        </a>
                    </li>
                @endif
            @endif
            @endforeach

            </ul>
        </div>
    </div>
<!-- END: Main Menu-->