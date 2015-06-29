$(document).ready(function(){



});

function plugin_genericradio_set_icon(element,icon){
	$(element).parent().parent().find('i').removeClass('btn-success');
	$('#iconGenericRadio').val(icon);
	$(element).addClass('btn-success');
}


function plugin_genericradio_save_settings(element){
	var form = $(".settings");
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

function plugin_genericradio_detectcode(detectbtn,text_field,type){
	$(detectbtn).attr("disabled",true);
	$(detectbtn).addClass("btn-warning");

	if(type == "rcswitch"){
		url = "action.php?action=genericRadio_rcswitchdetect";
	}

	if(type == "chacon"){
		url = "action.php?action=genericRadio_chacondetect";
	}

	$.ajax({
		url: url
	}).done(function(answer) {
		answer = answer.trim()
		switch(answer){
			case "Check Permissions":
			alert("Impossible de récupérer un code radio \n Vérifier les permissions, dans Préférences -> Relai RCSwitch");
			$(detectbtn).addClass("btn-danger");
			$(detectbtn).removeClass("btn-warning");
			break;

			case "Wrong GPIO":
			alert("Impossible de trouver un récepteur radio sur la pin défini, changer de pin dans Préférences -> Relai RCSwitch");
			$(detectbtn).addClass("btn-danger");
			$(detectbtn).removeClass("btn-warning");
			break;

			default:
			$("#"+text_field).val(answer);
			$(detectbtn).attr("disabled",false);
			$(detectbtn).removeClass("btn-warning");
			break;
		}

		console.log(answer);

	});
	
}