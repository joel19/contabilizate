@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid">
    	<!-- Breadcrumbs-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="{{ route('home') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Usuarios</li>
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
	          	<i class="fa fa-table"></i> Usuarios
	          	<button class="btn btn-md btn-primary pull-right" id="agregarBtn" data-toggle="modal" data-target="#agregarUsuario"><i class="fa fa-user-plus"></i> Agregar</button>
	        </div>
	        <div class="card-body">
	          	<div class="table-responsive">
		            <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
			            <thead>
			                <tr>
			                  	<th>Nombre</th>
			                  	<th>Apellidos</th>
			                  	<th>Email</th>
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
<div class="modal fade" id="agregarUsuario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      	<div class="modal-header agregarModal">
	        	<h5 class="modal-title" id="exampleModalLabel">Agregar Usuario</h5>
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

<!-- Editar Modal -->
<div class="modal fade" id="editarUsuario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      	<div class="modal-header editarModal">
	        	<h5 class="modal-title" id="exampleModalLabel">Editar Usuario</h5>
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
<div class="modal fade" id="eliminarUsuario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
	        			¿Deseas eliminar este usuario?
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
<script src="{{ asset('js/usuarios.js') }}"></script>
@endsection
<!-- D1F60B6E3CA14FF06A985DCEA0 -->