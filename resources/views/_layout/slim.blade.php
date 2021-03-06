<!DOCTYPE html>
<!--[if IE 8]> <html lang="{{ app()->getLocale() }}" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="{{ app()->getLocale() }}" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="{{ app()->getLocale() }}" class="no-js">
<!--<![endif]-->
<head>
  @include('_include.head')
  @include('_include.asset_top_slim')
</head>
<body class="vertical-layout vertical-menu-modern 1-columns navbar-floating footer-static bg-full-screen-image menu-collapsed blank-page blank-page ekkr-template-slim" 
      data-open="click" data-menu="vertical-menu-modern" data-col="1-columns">
  
  @include('_include.alert')
  @yield('content')
  <!-- include('script.general') -->
  <!-- include('script.page') -->
  @include('_include.asset_bottom_slim')
    
</body>
</html>