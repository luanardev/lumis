
<aside class="control-sidebar control-sidebar-light">
    
		<ul class="nav nav-pills nav-sidebar flex-column" >
			<li class="nav-item">
				<a href="{{route('dashboard')}}" class="nav-link">
					<i class="nav-icon fas fa-cubes"></i>
					<p>Dashboard</p>
				</a>
			</li>

			@if( Auth::user()->hasApps())
				@foreach(Auth::user()->getApps() as $app)
				<li class="nav-item">
					<a href="{{url($app->url)}}" target="_blank" class="nav-link">
						<i class="nav-icon fas fa-cubes"></i>
						<p>{{$app->display_name}}</p>
					</a>
				</li>
				@endforeach
			@endif
		</ul>
	
</aside>