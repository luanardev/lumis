@extends('layouts.master')

@section('body-class', 'hold-transition login-page')

@section('body')

<div class="login-box">


	<div class="card card-outline card-primary">
		<div class="card-header">
			<div class="text-center py-lg-1">

				@php $logo = Organization::get('logo') @endphp
				@if(!is_null($logo)  )
					<img src="{{ asset("storage/".$logo) }}" class="img-fluid" />
				@else
					<span class="h1 brand-text font-weight-light">{{config('app.name')}}</span>
				@endif

			</div>
		</div>
		<div class="card-body">
			<p class="login-box-msg">Sign in to start your session</p>

			<form action="{{route('login')}}" method="post">
				@csrf
				<div class="input-group mb-3">
					<input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Email" required>
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-envelope"></span>
						</div>
					</div>
					@error('email')
					  <span class="error invalid-feedback">{{ $message }}</span>
					@enderror
				</div>
				<div class="input-group mb-3">
					<input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
					<div class="input-group-append">
						<div class="input-group-text">
							<span class="fas fa-lock"></span>
						</div>
					</div>
					@error('password')
					  <span class="error invalid-feedback">{{ $message }}</span>
					@enderror
				</div>
				<div class="row">
					<div class="col-8">
						<div class="icheck-primary">
							<input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
							<label for="remember">
								Remember Me
							</label>
						</div>
					</div>

					<div class="col-4">
						<button type="submit" class="btn btn-primary btn-block">Sign In</button>
					</div>

				</div>
			</form>

			<p class="mb-1">
				<a href="{{route('password.request')}}">I forgot my password</a>
			</p>

		</div>
	</div>
</div>


@endsection
