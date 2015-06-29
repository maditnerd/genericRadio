$(document).ready(function(){



});

function plugin_genericradio_set_icon(element,icon){
	$(element).parent().parent().find('i').removeClass('btn-success');
	$('#iconGenericRadio').val(icon);
	$(element).addClass('btn-success');
}


function plugin_genericradio_save_settings(element){
	var form = $(element).parent();
 	var data = form.toData();
 	data.action = 'genericRadio_plugin_setting'
	$.action(data,
		function(response){
			alert(response.message);
			location.reload();
		}
	);
}
//Ajout / Modification
function plugin_genericradio_save(element){
	var form = $(element).closest('fieldset');
 	var data = form.toData();
 	data.action = 'genericRadio_save_genericRadio'
	$.action(data,
		function(response){
			alert(response.message);
			form.find('input').val('');
			location.reload();
		}
	);
}

//Import
function plugin_genericradio_import(element){
	var form = $(".import");
 	var data = form.toData();
 	console.log(data);
 	data.action = 'genericRadio_import_genericRadio'
	$.action(data,
		function(response){
			alert(response.message);
			form.find('input').val('');
			location.reload();
		}
	);
}

//Supression
function plugin_genericradio_delete(id,element){

	if(!confirm('Êtes vous sûr de vouloir faire ça ?')) return;
	$.action(
		{
			action : 'genericRadio_delete_genericRadio', 
			id: id
		},
		function(response){
			$(element).closest('tr').fadeOut();
		}
	);

}