@extends('layouts.master')

@section('body-class', 'hold-transition login-page')

@section('body')

<div class="login-box">
  <!-- /.login-logo -->
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="#" class="h1">{{config('app.name')}}</a>
        </div> 
        <div class="card-body">
            <p class="login-box-msg">Please confirm your password before continuing</p>
            <form action="{{route('password.confirm')}}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required autocomplete="current-password">
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
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Confirm password</button>
                    </div>
                    <!-- /.col -->
                </div>
                
            </form>

            <p class="mt-3 mb-1">
                <a href="{{route('login')}}">Sign in</a>
            </p>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.login-box -->

@endsection