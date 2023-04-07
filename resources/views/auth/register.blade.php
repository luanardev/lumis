@extends('layouts.master')

@section('body-class', 'hold-transition register-page')

@section('body')
<div class="register-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="#" class="h1">{{config('app.name')}}</a>
        </div>
        <div class="card-body">
        <p class="login-box-msg">Register for Account</p>

        <form action="{{route('register')}}" method="post">
            @csrf
            <div class="input-group mb-3">
                <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Full name" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
                @error('name')
                <span class="error invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
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
                <input type="password" id="password" name="password" value="{{ old('password') }}" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
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
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Retype password" required>
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
                <div class="col-8">
                    <div class="icheck-primary">
                        <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                        <label for="agreeTerms">I agree to the <a href="#">terms</a> </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">Register</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <a href="{{route('login')}}" class="text-center">I already have an account</a>
        </div>
        <!-- /.form-box -->
    </div><!-- /.card -->
</div>
@endsection