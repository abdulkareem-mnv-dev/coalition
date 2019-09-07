<!DOCTYPE html>
<html>
<head>
	<title>Coalition | @yield('title')</title>
	@include('_meta')
	@include('_styles')
	@yield('css')
</head>
<body>

	@yield('content')

	@include('_scripts')
	@yield('js')

</body>
</html>