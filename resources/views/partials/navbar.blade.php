 <!-- Main Header -->
 <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
				<i class="fas fa-bars"></i>
			</a>
        </li>

    </ul>

	@hasSection('navbar')
		@yield('navbar')
	@endif

    <ul class="navbar-nav ml-auto">
        @auth

		<li class="nav-item dropdown">
			<a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">
				{{ Auth::user()->name }}
			</a>
			<ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
				<li class="dropdown-submenu dropdown-hover">
					<a tabindex="-1" href="{{route('dashboard')}}" class="dropdown-item">
						Dashboard
					</a>
				</li>

				<li class="dropdown-submenu dropdown-hover">
					<a tabindex="-1" href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
						Sign Out
					</a>
					<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
						@csrf
					</form>
				</li>

			</ul>
		</li>
		@endauth

        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
              <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
              <i class="fas fa-th-large"></i>
            </a>
        </li>
    </ul>

</nav>
