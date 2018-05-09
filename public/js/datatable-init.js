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
            "defaultContent": "<button id='editarBtn' style='width:78.23px !important;' class='btn btn-sm btn-info' data-toggle='modal' data-target='#editarUsuario'><i class='fa fa-edit'></i>Editar</button> <button id='eliminarBtn' class='btn btn-sm btn-danger' data-toggle='modal' data-target='#eliminarUsuario'><i class='fa fa-trash'></i>Eliminar</button>"
        } ]


});

