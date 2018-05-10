@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid">
    	<!-- Breadcrumbs-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="{{ route('home') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Contribuyentes</li>
        </ol>
        @if (Session::has('respuesta'))

	        <div class="alert {!!session('respuesta')[1]!!} alert-dismissible fade show" role="alert" id="flashMessage">
			  	<strong id="respuestaContr">{!! session('respuesta')[0] !!}</strong>
			  	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			    	<span aria-hidden="true">&times;</span>
			 	</button>
			</div>
	   @endif
        <div class="alert alert-success alert-dismissible fade" role="alert" id="alertaRespuesta">
		  	<strong id="respuestaContr"></strong>
		  	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		 	</button>
		</div>
        <!-- Example DataTables Card-->
      	<div class="card mb-3">
	        <div class="card-header">
	          	<i class="fa fa-table"></i> Contribuyentes
	          	<button class="btn btn-md btn-primary pull-right" id="agregarBtn" data-toggle="modal" data-target="#agregarContribuyente"><i class="fa fa-user-plus"></i> Agregar</button>
	        </div>
	        <div class="card-body">
	          	<div class="table-responsive">
		            <table class="table table-hover" id="cbyTable" width="100%" cellspacing="0">
			            <thead>
			                <tr>
			                	<th>ID</th>
			                  	<th>Nombre</th>
			                  	<th>RFC</th>
			                  	<th>Régimen</th>
			                  	<th>Fecha de Alta</th>
			                  	<th>Operaciones</th>
			            	</tr>
		              	</thead>			 
		            </table>
		        </div>
	        </div>
      	</div>
    </div>
    <!-- Agregar Modal -->
<div class="modal fade" id="agregarContribuyente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      	<div class="modal-header agregarModal">
	        	<h5 class="modal-title" id="exampleModalLabel">Agregar Contribuyente</h5>
	        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          		<span aria-hidden="true" class="text-white">&times;</span>
	        	</button>
	      	</div>
	      	<form method="POST" action="{{ route('contribuyentes.store') }}" enctype="multipart/form-data" id="addContriFrom" name="addContriFrom">
	      	<div class="modal-body">
                {{ csrf_field() }}
                <div class="form-group">
                	<label for="name">Nombre o razón social:</label>
                	<input type="text" name="name" id="name" class="form-control" placeholder="Nombre del contribuyente" required minlength="2" ng-model="contribuyente.nombre" ng-pattern="'[a-zA-ZñÑáéíóúÁÉÍÓÚ\\s]+'">
                	<div class="invalid-feedback" ng-class="{'show': addContriFrom.name.$dirty && addContriFrom.name.$invalid}" ng-if="addContriFrom.name.$dirty && addContriFrom.name.$invalid">
                            <p ng-if="addContriFrom.name.$error.minlength" class="error-text"><strong>El nombre debe contener al menos 2 caracteres.</strong></p>
                            <p ng-if="addContriFrom.name.$error.pattern" class="error-text"><strong>El nombre debe contener solo letras.</strong></p>
                    </div>
                </div>
                <div class="form-group">
                	<label for="rfc">RFC</label>
                	<input type="text" name="rfc" id="rfc" class="form-control" placeholder="RFC del contribuyente" required minlength="12" maxlength="20" ng-model="contribuyente.rfc" ng-pattern="/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/">
                	<div class="invalid-feedback" ng-class="{'show': addContriFrom.rfc.$dirty && addContriFrom.rfc.$invalid}" ng-if="addContriFrom.rfc.$dirty && addContriFrom.rfc.$invalid">
                		<p ng-if="addContriFrom.rfc.$error.required" class="error-text"><strong>El campo RFC es obligatorio</strong></p>
                        <p ng-if="addContriFrom.rfc.$error.pattern" class="error-text"><strong>El RFC no tiene un formato valido</strong></p>
                    </div>
                </div>
                <div class="form-group">
                	<label for="regimen_id">Régimen</label>
                    <select name="regimen_id" id="regimen_id" class="form-control" ng-model="contribuyente.regimen" required>
                  		<option value="" selected disabled>Selecciona un régimen</option>
                      	@foreach ($regimenes as $regimen)
                  		<option value="{{$regimen->id}}">{{$regimen->description}}</option>
                      	@endforeach       
                     </select>                 
                  	<div class="invalid-feedback" ng-class="{'show': addContriFrom.regimen_id.$touched && addContriFrom.regimen_id.$invalid}" ng-if="addContriFrom.regimen_id.$touched && addContriFrom.regimen_id.$invalid">
                        <strong>El campo régimen es obligatorio</strong>
                    </div>
                </div>
                <hr> 
                <p class="text-center">
                	Certificado Digital
                </p>
                <hr>
                <div class="form-group">
                	<label for="cer">Archivo .cer</label>
                	<input type="file" name="cer" id="cer" class="form-control" accept=".cer" ng-model="contribuyente.cer">
                	<div class="invalid-feedback" id="errorCer">
                        <strong>El archivo debe tener una extensión .cer</strong>
                    </div>          
                </div>
                <div class="form-group">
                	<label for="key">Archivo .key</label>
                	<input type="file" name="key" id="key" class="form-control" accept=".key">
                	<div class="invalid-feedback" id="errorKey">
                        <strong>El archivo debe tener una extensión .cer</strong>
                    </div>
                </div>
                <div class="form-group">
                	<label for="pass_key">Contraseña de la llave privada:</label>
                	<input type="text" name="pass_key" id="pass_key" class="form-control" placeholder="Contraseña">
                </div>
	      	</div>
	      	<div class="modal-footer">
	        	<button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelAddForm">Cancelar</button>
	        	<button type="submit" class="btn btn-primary" ng-disabled="addContriFrom.$invalid">Guardar</button>
	      	</div>
	      	</form>
	    </div>
  	</div>
</div>

<!-- Editar Modal -->
<div class="modal fade" id="editarContribuyente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      	<div class="modal-header editarModal">
	        	<h5 class="modal-title" id="editCTitle"></h5>
	        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          		<span aria-hidden="true" class="text-white">&times;</span>
	        	</button>
	      	</div>
	      	<form method="POST"  enctype="multipart/form-data" id="editContrForm" >
	      	<div class="modal-body">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="PUT">
                <div class="form-group">
                	<label for="name">Nombre o razón social:</label>
                	<input type="text" name="name" id="ename" class="form-control" placeholder="Nombre del contribuyente" required minlength="2" ng-model="contribuyenteEdit.nombre" ng-pattern="'[a-zA-ZñÑáéíóúÁÉÍÓÚ\\s]+'">
                	<div class="invalid-feedback" ng-class="{'show': editContrForm.name.$dirty && editContrForm.name.$invalid}" ng-if="editContrForm.name.$dirty && editContrForm.name.$invalid">
                        <strong>
                            <p ng-if="editContrForm.name.$error.minlength" class="error-text"><strong>El nombre debe contener al menos 2 caracteres.</strong></p>
                            <p ng-if="editContrForm.name.$error.pattern" class="error-text"><strong>El nombre debe contener solo letras.</strong></p>
                        </strong>
                    </div>
                </div>
                <div class="form-group">
                	<label for="rfc">RFC</label>
                	<input type="text" name="rfc" id="erfc" class="form-control" placeholder="RFC del contribuyente" required minlength="12" maxlength="20" ng-model="contribuyenteEdit.rfc" ng-pattern="/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/">
                	<div class="invalid-feedback" ng-class="{'show': editContrForm.rfc.$dirty && editContrForm.rfc.$invalid}" ng-if="editContrForm.rfc.$dirty && editContrForm.rfc.$invalid">
                		<p ng-if="editContrForm.rfc.$error.required" class="error-text"><strong>El campo RFC es obligatorio</strong></p>
                        <p ng-if="editContrForm.rfc.$error.pattern" class="error-text"><strong>El RFC no tiene un formato valido</strong></p>
                    </div>
                </div>
                <div class="form-group">
                	<label for="regimen_id">Régimen</label>
                    <select name="regimen_id" id="eregimen_id" class="form-control" ng-model="contribuyenteEdit.regimen" required>
                  		<option value="" selected disabled>Selecciona un régimen</option>
                      	@foreach ($regimenes as $regimen)
                  		<option value="{{$regimen->id}}">{{$regimen->description}}</option>
                      	@endforeach       
                     </select>                 
                  	<div class="invalid-feedback" ng-class="{'show': editContrForm.regimen_id.$touched && editContrForm.regimen_id.$invalid}" ng-if="editContrForm.regimen_id.$touched && editContrForm.regimen_id.$invalid">
                        <strong>El campo régimen es obligatorio</strong>
                    </div>
                </div>
                <hr> 
                <p class="text-center">
                	Certificado Digital          
                </p>
                <hr>
                <div class="form-group">
                	<label for="cer">Archivo .cer</label>
                	<input type="file" name="cer" id="ecer" class="form-control" accept=".cer">
                	<div class="invalid-feedback" id="errorEditCer">
                        <strong>El archivo debe tener una extensión .cer</strong>
                    </div>          
                </div>
                <div class="form-group">
                	<label for="key">Archivo .key</label>
                	<input type="file" name="key" id="ekey" class="form-control" accept=".key">
                	<div class="invalid-feedback" id="errorEditKey">
                        <strong>El archivo debe tener una extensión .cer</strong>
                    </div>
                </div>
                <div class="form-group">
                	<label for="pass_key">Contraseña de la llave privada:</label>
                	<input type="text" name="pass_key" id="epass_key" class="form-control" placeholder="Contraseña">
                </div>
	      	</div>
	      	<div class="modal-footer">
	        	<button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelEditForm">Cancelar</button>
	        	<button type="submit" class="btn btn-primary" id="editartb" ng-disabled="editContrForm.$invalid">Actualizar</button>
	      	</div>
	      	</form>
	    </div>
  	</div>
</div>

<!-- Eliminar Modal -->
<div class="modal fade" id="eliminarContribuyente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      	<div class="modal-header eliminarModal">
	        	<h5 class="modal-title" id="nameModalTitle"></h5>
	        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          		<span aria-hidden="true" class="text-white">&times;</span>
	        	</button>
	      	</div>
	      	<form method="POST" id="deleteContrForm">
	      	<div class="modal-body">
		      			{{ csrf_field() }}
	        	 		<input type="hidden" name="_method" value="DELETE">
	        	 		<input type="hidden" name="id" id="id" >
	        			¿Deseas eliminar este contribuyente?
	      	</div>
	      	<div class="modal-footer">
	        	<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
	        	<button type="submit" class="btn btn-primary button-eliminar">Aceptar</button>
	      	</div>
	      	</form>
	    </div>
  	</div>
</div>
</div>


@endsection

@section('scripts')
<script src="{{ asset('js/contribuyentes.js') }}"></script>
@endsection
<!-- D1F60B6E3CA14FF06A985DCEA0 -->