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
        <div class="alert alert-success alert-dismissible fade" role="alert" id="alertaRespuesta">
		  	<strong id="respuesta"></strong>
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
	      	<form role="form" method="POST" action="{{ route('contribuyentes.store') }}" enctype="multipart/form-data">
	      	<div class="modal-body">
                {{ csrf_field() }}
                <div class="form-group">
                	<label for="name">Nombre o razón social:</label>
                	<input type="text" name="name" id="name" class="form-control" placeholder="Nombre del contribuyente">
                </div>
                <div class="form-group">
                	<label for="rfc">RFC</label>
                	<input type="text" name="rfc" id="rfc" class="form-control" placeholder="RFC del contribuyente">
                </div>
                <div class="form-group">
                	<label for="regimen_id">Régimen</label>
                      <select name="regimen_id" id="regimen_id" class="form-control">
                      		<option value="" selected disabled>Seleccona un régimen</option>
                      		@foreach ($regimenes as $regimen)
                  			<option value="{{$regimen->id}}">{{$regimen->description}}</option>
                      		@endforeach       
                      </select>
                </div>
                <hr> 
                <p class="text-center">
                	Certificado Digital
                </p>
                <hr>
                <div class="form-group">
                	<label for="cer">Archivo .cer</label>
                	<input type="file" name="cer" id="cer" class="form-control">
                </div>
                <div class="form-group">
                	<label for="key">Archivo .key</label>
                	<input type="file" name="key" id="key" class="form-control">
                </div>
                <div class="form-group">
                	<label for="pass_key">Contraseña de la llave privada:</label>
                	<input type="text" name="pass_key" id="pass_key" class="form-control" placeholder="Contraseña">
                </div>
	      	</div>
	      	<div class="modal-footer">
	        	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        	<button type="submit" class="btn btn-primary">Save changes</button>
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
	        	<h5 class="modal-title" id="exampleModalLabel">Editar Contribuyente</h5>
	        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          		<span aria-hidden="true" class="text-white">&times;</span>
	        	</button>
	      	</div>
	      	<div class="modal-body">
	        	...
	      	</div>
	      	<div class="modal-footer">
	        	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        	<button type="button" class="btn btn-primary">Save changes</button>
	      	</div>
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
	      	<form method="POST" id="deleteUserForm">
	      	<div class="modal-body">
		      			{{ csrf_field() }}
	        	 		<input type="hidden" name="_method" value="DELETE">
	        	 		<input type="hidden" name="id" id="id" >
	        			¿Deseas eliminar este contribuyente?
	      	</div>
	      	<div class="modal-footer">
	        	<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
	        	<button type="submit" class="btn btn-primary">Aceptar</button>
	      	</div>
	      	</form>
	    </div>
  	</div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/contribuyentes.js') }}"></script>
@endsection
<!-- D1F60B6E3CA14FF06A985DCEA0 -->