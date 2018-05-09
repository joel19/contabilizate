@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-login mx-auto mt-2">
        <div class="card-header text-center"><i class="fa fa-address-card"></i> Registro</div>
        <div class="card-body">
            <form class="form-horizontal" method="POST" action="{{ route('register') }}" name="registerForm">
                {{ csrf_field() }}
                <div class="col-md-12 form-group">
                    <label for="name" class="control-label">Nombre:</label>
                    <div>
                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus ng-model="register.name" minlength="2" ng-pattern="'[a-zA-ZñÑáéíóúÁÉÍÓÚ\\s]+'">
                        <div class="invalid-feedback" ng-class="{'show': registerForm.name.$dirty && registerForm.name.$invalid}" ng-if="registerForm.name.$dirty && registerForm.name.$invalid">
                            <strong>
                                <p ng-if="registerForm.name.$error.minlength" class="error-text">El nombre debe contener al menos 2 caracteres.</p>
                                <p ng-if="registerForm.name.$error.pattern" class="error-text">El nombre debe contener solo letras.</p>
                            </strong>
                        </div>
                        @if ($errors->has('name'))
                            <div class="invalid-feedback show">
                                <strong>{{ $errors->first('name') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-12 form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                    <label for="last_name" class="control-label">Apellidos:</label>
                    <div>
                        <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required autofocus ng-model="register.last_name" minlength="2" ng-pattern="'[a-zA-ZñÑáéíóúÁÉÍÓÚ\\s]+'">
                        <div class="invalid-feedback" ng-class="{'show': registerForm.last_name.$dirty && registerForm.last_name.$invalid}" ng-if="registerForm.last_name.$dirty && registerForm.last_name.$invalid">
                            <strong>
                                <p ng-if="registerForm.last_name.$error.minlength" class="error-text">Los apellidos deben contener al menos 2 caracteres.</p>
                                <p ng-if="registerForm.last_name.$error.pattern" class="error-text">Los apellidos deben contener solo letras.</p>
                            </strong>
                        </div>
                        @if ($errors->has('last_name'))
                            <div class="invalid-feedback show">
                                <strong>{{ $errors->first('last_name') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-12 form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="control-label">Correo Electrónico:</label>
                    <div>
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required ng-model="register.email">
                        <div class="invalid-feedback" ng-class="{'show': registerForm.email.$dirty && registerForm.email.$invalid}" ng-if="registerForm.email.$dirty && registerForm.email.$invalid">
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
                        <input id="password" type="password" class="form-control" name="password" required minlength="6" ng-model="register.password">
                        <div class="invalid-feedback" ng-class="{'show': registerForm.password.$dirty && registerForm.password.$invalid}" ng-if="registerForm.password.$dirty && registerForm.password.$invalid">
                            <strong>La contraseña debe contener al menos 6 caracteres.</strong>
                        </div>
                        @if ($errors->has('password'))
                            <div class="invalid-feedback show">
                                <strong>{{ $errors->first('password') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-12 form-group" ng-if="registerForm.password.$valid">
                    <label for="password-confirm" class="control-label">Confirma tu contraseña:</label>
                    <div>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required compare-to="register.password" ng-model="register.confirm">
                        <div class="invalid-feedback" ng-class="{'show': registerForm.password_confirmation.$dirty && registerForm.password_confirmation.$invalid}" ng-if="registerForm.password_confirmation.$dirty && registerForm.password_confirmation.$invalid">
                            <strong>Las contraseñas no coinciden.</strong>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-block" ng-disabled="registerForm.$invalid">
                            Registro
                        </button>
                    </div>
                </div>
            </form>
            <div class="text-center">
              <a class="d-block  mt-3" href="/">Inicia Sesión</a>
            </div>
        </div>
    </div>
</div>
@endsection
