@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-login mx-auto mt-5">
        <div class="card-header text-center"><i class="fa fa-fw fa-sign-in"></i> Inicia Sesión</div>
        <div class="card-body">
            <form method="POST" action="{{ route('login') }}" name="loginForm">
                {{ csrf_field() }}
                <div class="col-md-12 mb-3 form-group">
                    <label for="email" class="control-label">Correo Electrónico:</label>
                    <div>
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus ng-model="user.email">
                        
                        <div class="invalid-feedback" ng-class="{'show': loginForm.email.$dirty && loginForm.email.$invalid}" ng-if="loginForm.email.$dirty && loginForm.email.$invalid">
                            <strong>Escribe un correo válido</strong>
                        </div>
                        @if ($errors->has('email'))
                            <div class="invalid-feedback show">
                                <strong>{{ $errors->first('email') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-12 form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="control-label">Contraseña:</label>
                    <div>
                        <input id="password" type="password" class="form-control" name="password" required ng-model="user.password" minlength="6">
                        <div class="invalid-feedback" ng-class="{'show': loginForm.password.$dirty && loginForm.password.$invalid}" ng-if="loginForm.password.$dirty && loginForm.password.$invalid">
                            <strong>La contraseña debe contener al menos 6 caracteres.</strong>
                        </div>
                        @if ($errors->has('password'))
                        <div class="invalid-feedback show">
                            <strong>{{ $errors->first('password') }}</strong>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Recordar mis datos
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <div>
                        <button type="submit" class="btn btn-primary btn-block" ng-disabled="loginForm.$invalid">
                            Login
                        </button>
                    </div>
                </div>
            </form>
        </div> 
        <div class="text-center">
            <a class="d-block mt-3 mb-3" href="{{ route('register') }}">Registrate</a>
        </div>
    </div>
</div>
@endsection
