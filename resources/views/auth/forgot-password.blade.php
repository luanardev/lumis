@extends('layouts.master')

@section('body-class', 'hold-transition login-page')

@section('body')

<div class="login-box">
  <!-- /.login-logo -->
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <div class="text-center py-lg-1">

                @php $logo = Organization::get('org_logo'); @endphp
                @if(!is_null($logo)  )
                    <img src="{{ asset("storage/{$logo}") }}" class="img-fluid" />
                @else
                    <span class="h1 brand-text font-weight-light">{{config('app.name')}}</span>
                @endif

            </div>
        </div> 
        <div class="card-body">
            <p class="login-box-msg">You forgot your password? You can retrieve a new password.</p>
            @if (session('status'))
                <div class="alert alert-success">
                    <p>{{ session('status') }}</p>
                </div>
            @endif
            <form action="{{route('password.email')}}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Email" required >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    @error('email')
                    <span class="error invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                    
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Request new password</button>
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