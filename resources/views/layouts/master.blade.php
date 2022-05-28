
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
		<meta charset="UTF-8">
		<title>{{ config('app.name') }}</title>
		<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
			  integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog=="
			  crossorigin="anonymous"/>
		@yield('main_css')
		
		@include('plugins.css')
		
		@livewireStyles
	
		@yield('vendor_css')
		
	</head>
    <body class="@yield('body-class')">

		@yield('body')	

		@include('plugins.js')

		@livewireScripts
		
		@yield('vendor_js')
		
    </body>
</html>

