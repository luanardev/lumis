
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
		<meta charset="UTF-8">
		<title>
			@hasSection('title')
				@yield('title')
			@else
				{{ config('app.name') }}
			@endif
		</title>

		<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

		@hasSection('favicon')
            @yield('favicon')
        @endif

		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
			  integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog=="
			  crossorigin="anonymous"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
		@yield('main_css')

		<link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
		<link rel="stylesheet" href="{{ asset('css/custom.css') }}">

		@include('plugins.css')

		@livewireStyles

		@yield('vendor_css')

	</head>
    <body class="@yield('body-class')">

		@yield('body')

		@include('plugins.js')

		<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
		<script src="{{ asset('js/custom.js') }}"></script>

		@livewireScripts

		@yield('vendor_js')

    </body>
</html>

