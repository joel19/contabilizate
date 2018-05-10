$(document).ready(function() {
	var data;
    var host = window.location.origin;
	var apiurl = host + "/api/contribuyentes/";
    
	var table = $('#cbyTable').DataTable({
        "order": [[ 0, "desc" ]],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
            "searchPlaceholder": "RFC o nombre"
        },
        "ajax": {
			        url: apiurl,
			       	dataSrc: '',
                    'beforeSend': function (request) {
                        request.setRequestHeader("Content-Type", 'application/json');
                    }
			    },
		"columns": [
                    { data: 'id' },
			        { data: 'name' },
			        { data: 'rfc' },
			        { data: 'regimen_description', searchable: false },
			        { data: 'alta' , searchable: false},		
			        { data: 'operaciones' },				
			    ],
	    "columnDefs": [ {
            "targets": -1,
            "data": null,
            "defaultContent": "<button id='editarBtn' class='btn btn-sm btn-info btn-table' data-toggle='modal' data-target='#editarContribuyente' ><i class='fa fa-edit'></i>Editar</button> "+
            "<button id='eliminarBtn' class='btn btn-sm btn-danger' style='margin-bottom: 5%;' data-toggle='modal' data-target='#eliminarContribuyente'><i class='fa fa-trash'></i>Eliminar</button> "
        } , {
                "targets": 0,
                "visible": false,
                "searchable": false
            }]
	});


	$('#cbyTable tbody').on( 'click', 'button', function () {
        data = table.row( $(this).parents('tr') ).data();
    	$("#eliminarContribuyente").on('shown.bs.modal', function(e){
			var id = data.id;
			$("#nameModalTitle").text(data.name + " - " + data.rfc);
			$('#deleteContrForm').attr("action", apiurl + id);
			$('#deleteContrForm :input#id').attr("value", id);
		});

		$("#editarContribuyente").on('shown.bs.modal', function(e){
            $("#editContrForm select#eregimen_id option").removeAttr("selected");
			var id = data.id;
            $("#editCTitle").text(data.name + " - " + data.rfc);
            $('#editContrForm').attr("action", apiurl + id);
			$('#editContrForm :input#ename').attr("value", data.name);
            $('#editContrForm :input#erfc').attr("value", data.rfc);
            $('#editContrForm :input#epass_key').attr("value", data.pass_key);
            $("#editContrForm select#eregimen_id  option[value="+ data.regimen_id +"]").attr("selected","selected");
		});
    } );


    $("#editarContribuyente").on('shown.bs.modal', function(e){
        $('#editContrForm')[0].reset();
    });

    $("#agregarContribuyente").on('shown.bs.modal', function(e){
        $('#addContriFrom')[0].reset();
    });

    $("#cancelAddForm").on('click', function(event) {
        $('#addContriFrom')[0].reset();
    });

    $("#cancelEditForm").on('click', function(event) {
        $('#editContrForm')[0].reset();
    });



	$("#cer").change(function() {
		$("#errorCer").removeClass('show');
		var files = $('#cer').prop("files")
		var ext = files[0].name.split('.').pop();
		if (ext != "cer") {
			$("#errorCer").addClass('show');
		}
    });

    $("#key").change(function() {
		$("#errorKey").removeClass('show');
		var files = $('#key').prop("files")
		var ext = files[0].name.split('.').pop();
		if (ext != "key") {
			$("#errorKey").addClass('show');
		}
    });

    setTimeout(function(){
        $("#flashMessage").remove();
    }, 4000);


    $("#deleteContrForm").on('submit', function(event) {
    	event.preventDefault();	
    	var contribuyente = $( this ).serializeArray();
    	var urlP = $(this).attr("action");

    	$.ajax({
    		url: urlP,
    		type: 'POST',
    		data: contribuyente,
    	})
    	.done(function(resp) {
    		table.ajax.reload();
    		$('#eliminarContribuyente').modal('hide');
    		$("#respuestaContr").text(resp.mensaje);
    		$(".alert-dismissible").addClass('show');
    		setTimeout(function(){
    			$(".alert-dismissible").removeClass('show');
    			$(".alert-dismissible").addClass('hide');
    		}, 4000);
    		
    	})
    	.fail(function(error) {
    		table.ajax.reload();
            $('#eliminarContribuyente').modal('hide');
            $("#respuestaContr").text("Ha ocurrido un error al eliminar el Contribuyente - " + error.status + error.statusText);
            $(".alert-dismissible").removeClass('alert-success');
            $(".alert-dismissible").addClass('alert-danger');
            $(".alert-dismissible").addClass('show');
            setTimeout(function(){
                $(".alert-dismissible").removeClass('show');
                $(".alert-dismissible").addClass('hide');
            }, 4000);
    	});
    	
    	
    });

    $("#editartb").on('click', function(event) {
        $("#editContrForm").submit();
    });
});

