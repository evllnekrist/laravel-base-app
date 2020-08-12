<!DOCTYPE html>
<!--[if IE 8]> <html lang="{{ app()->getLocale() }}" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="{{ app()->getLocale() }}" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="{{ app()->getLocale() }}" class="no-js">
<!--<![endif]-->
<head>
  @include('_include.head')
  @include('_include.asset_top')
</head>
<body class="vertical-layout vertical-menu-modern 2-columns navbar-floating footer-static menu-collapsed ekkr-template-default" 
      data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
  
  @include('_include.nav_side')
  @include('_include.header')
  @include('_include.alert')
  @yield('content')
  @include('_include.page_loader')
  <!-- include('_include.partner_logo') -->
  @include('_include.footer')
  <!-- include('script.general') -->
  <!-- include('script.page') -->
  @include('_include.asset_bottom')
    
</body>
</html>