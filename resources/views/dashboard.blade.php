@extends('adminlte::layouts.master')

@section('body-class', 'hold-transition layout-top-nav')

@section('body')

<div class="wrapper">

	<!-- Navbar -->
	<nav class="main-header navbar navbar-expand-md navbar-light navbar-white">

		<div class="container">
			<ul class="navbar-nav">
				<li class="nav-item">
					<a href="{{route('dashboard')}}" class="h3">
						@php $logo = OrgSettings::get('company_logo'); @endphp
						@if(!is_null($logo)  )   
							<img src="{{ asset("storage/{$logo}") }}" class="img-fluid" style="height:50px" />
						@else
							<span class="brand-text font-weight-light">{{config('app.name')}}</span>
						@endif
						
					</a>
				</li>
			
			</ul>


			<button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse order-3" id="navbarCollapse">

				<ul class="navbar-nav">
					@livewire('institution::campus-switcher')
				</ul>

				<!-- Right navbar links -->
				<ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">


					@auth
					<li class="nav-item dropdown user-menu">
						<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
							<span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
						</a>
						<ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
							<!-- User image -->
							<li class="user-header bg-primary">
								<p>
									{{ Auth::user()->name }}
									<small>Member since {{ Auth::user()->created_at->format('M. Y') }}</small>
								</p>
							</li>
							<!-- Menu Footer-->
							<li class="user-footer">
								<a href="#" class="btn btn-default btn-flat">Profile</a>
								<a href="#" class="btn btn-default btn-flat float-right"
								   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
									Sign out
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

			</div>
		</div>
	</nav>

	<div class="content-wrapper">
		<div class="content">
			<div class="container pt-4">
				<div class="row">
					@if( Auth::user()->hasApps())
						@foreach(Auth::user()->getApps() as $app)
						<div class="col-lg-2 col-md-4 col-sm-6">
							<a href="{{url($app->url)}}" target="_blank">
								<div class="card card-widget widget-user">									
									<div class="widget-user-header">
										<img class="img-circle" src="{{asset('assets/images/app.png')}}" width="50px" />										
										<h6 class="widget-user-desc text-center">{{$app->display_name}}</h6>
																			
									</div>									
								</div>
							</a>
						</div>
						@endforeach
					@else
						<div class="offset-lg-3 pt-lg-4">
							<div class="alert alert-danger">
								<h6>Apps not allocated. Please consult the system administrator</h6>
							</div>
						</div>
					@endif
				</div>
			</div>
		</div>

	</div>
	<aside class="control-sidebar control-sidebar-light">
        @include('layouts.control')
	</aside>
	@include('adminlte::partials.footer')

</div>
@endsection

