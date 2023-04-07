@extends('layouts.master')

@section('body-class', 'hold-transition login-page')

@section('body')

<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="#" class="h1">{{config('app.name')}}</a>
        </div>

        <div class="card-body">
            <p class="login-box-msg">Enter your new password now.</p>
         
            <form action="{{ route('password.update') }}" method="post" >
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="input-group mb-3">
                    <input type="email" id="email" name="email" value="{{old('email', $request->email)}}" class="form-control @error('email') is-invalid @enderror" placeholder="Email" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @error('email')
                    <span class="error invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required autocomplete="new-password" >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @error('password')
                    <span class="error invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control  @error('password_confirmation') is-invalid @enderror"" placeholder="Confirm Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @error('password_configmation')
                    <span class="error invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Reset password</button>
                    </div>
                </div>
            </form>
            <p class="mt-3 mb-1">
                <a href="{{route('login')}}">Sign in</a>
            </p>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->

@endsection