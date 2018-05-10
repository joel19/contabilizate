$(document).ready(function() {
	var data;
	var apiurl = "api/usuarios/";
	var table = $('#dataTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
        "ajax": {
			        url: apiurl,
			       	dataSrc: ''
			    },
		"columns": [
			        { data: 'name' },
			        { data: 'last_name' },
			        { data: 'email' },		
			        { data: 'operaciones' },				
			    ],
	    "columnDefs": [ {
            "targets": -1,
            "data": null,
            "defaultContent": "<button id='editarBtn' class='btn btn-sm btn-info btn-table' data-toggle='modal' data-target='#editarUsuario' ><i class='fa fa-edit'></i>Editar</button> <button id='eliminarBtn' class='btn btn-sm btn-danger' style='margin-bottom: 5%;' data-toggle='modal' data-target='#eliminarUsuario'><i class='fa fa-trash'></i>Eliminar</button>"
        } ]
	});

	$('#dataTable tbody').on( 'click', 'button', function () {
        data = table.row( $(this).parents('tr') ).data();
    	$("#eliminarUsuario").on('shown.bs.modal', function(e){
			var id = data.id;
			$("#nameModalTitle").text(data.name + " " + data.last_name);
			$('#deleteUserForm').attr("action", apiurl + id);
			$('#deleteUserForm :input#id').attr("value", id);
		});

		$("#editarUsuario").on('shown.bs.modal', function(e){
			var id = data.id;
			$("").text(data.name + " " + data.last_name);
			$('#deleteUserForm').attr("action", apiurl + id);
			$('#deleteUserForm :input#id').attr("value", id);
		});
    } );


    $("#deleteUserForm").on('submit', function(event) {
    	event.preventDefault();	
    	var user = $( this ).serializeArray();
    	var urlP = $(this).attr("action");

    	$.ajax({
    		url: urlP,
    		type: 'POST',
    		data: user,
    	})
    	.done(function(resp) {
    		console.log($("body").scrollTop());
    		table.ajax.reload();
    		$('#eliminarUsuario').modal('hide');
    		$("#respuesta").text(resp.mensaje);
    		$(".alert-dismissible").addClass('show');
    		setTimeout(function(){
    			$(".alert-dismissible").removeClass('show');
    			$(".alert-dismissible").addClass('hide');
    		}, 4000);
    		
    	})
    	.fail(function() {
    		console.log("error");
    	});
    	
    	
    });
});


function init(){
	$('#dataTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        },
        "ajax": {
			        url: 'api/usuarios',
			       	dataSrc: ''
			    },
		"columns": [
			        { data: 'name' },
			        { data: 'last_name' },
			        { data: 'email' },		
			        { data: 'operaciones' },				
			    ],
	    "columnDefs": [ {
            "targets": -1,
            "data": null,
            "defaultContent": "<button id='editarBtn'  class='btn btn-sm btn-info btn-table' data-toggle='modal' data-target='#editarUsuario' data-id='{{$usuario->id}}'><i class='fa fa-edit'></i>Editar</button> <button id='eliminarBtn' class='btn btn-sm btn-danger' data-toggle='modal' data-target='#eliminarUsuario'><i class='fa fa-trash'></i>Eliminar</button>"
        } ]


	});
}





