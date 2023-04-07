<div class="content-wrapper">
    {{-- Content Header --}}
    @hasSection('content-header')
	<div class="content-header">
		@yield('content-header')
	</div>
    @endif
	
    {{-- Main Content --}}
    <div class="content">
		@include('partials.flash')
		@yield('content')
    </div>
</div>