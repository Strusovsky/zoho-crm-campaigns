function close_window() {
	if (confirm("Close Window?")) {
		close();
	}
}

var unsaved = false;

$(":input").keyup(function() {
	unsaved = true;
	$(':button').on('click', function() {
		unsaved = false;
	});
});

$("select").change(function() {
	unsaved = true;
	$(':button').on('click', function() {
		unsaved = false;
	});
});

function unloadPage(){
	if(unsaved){
		return "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
	}
}

window.onbeforeunload = unloadPage;

var info = $('#status-info');

switch ($('#status').data('status'))
{
	case 'Planning':
		info.addClass('text-warning');
		break;
	case 'Active':
		info.addClass('text-success');
		break;
	case 'Inactive':
		info.addClass('text-error');
		break;
	case 'Complete':
		info.addClass('text-info');
		break;
}

/* Form Check and Submit */

var group					= $('div.control-group'),
	input					= $('div.control-group input[type="text"]'),
	error					= $('div.error'),
	updateCampaign			= $('#update_campaign'),
	insertCampaign			= $('#insert_campaign_add_seminar'),
	updateSeminar			= $('#update_seminar_info'),
	insertSeminar			= $('#insert_seminar_add_seminar'),
    buttonGroup             = $('#update_campaign, #insert_campaign_add_seminar, #update_seminar_info, #insert_seminar_add_seminar, #update_seminar_results, #insert_results_add_seminar'),
	form					= $('#form'),
	overlay					= $('.overlay');

group.removeClass('error');

input.each(function (i) {
	$(this).blur(function () {
		if (this.id == 'email') {
			var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			if (emailReg.test(this.value) && $.trim(this.value) !== '') {
				$('#' + this.name).removeClass('error');
			} else {
				$('#' + this.name).addClass('error');
			}
		} else if ($.trim(this.value) !== '') {
			$('#' + this.name).removeClass('error');
		} else {
			$('#' + this.name).addClass('error');
		}
	});
});

buttonGroup.on('click', function(e) {
    e.preventDefault();
    checkForm(this.form, this.name);
});

function checkForm(theForm, button) {
	data = new FormData();
	for (i = 0; i < theForm.elements.length; i++) {
		if (theForm.elements[i].type != 'submit') {
			data.append((theForm.elements[i].name), (theForm.elements[i].value));
			if (theForm.elements[i].type)

			if (theForm.elements[i].name == 'email') {
				var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
				if (!emailReg.test(theForm.elements[i].value) || $.trim(theForm.elements[i].value) === '') {
					$('#' + theForm.elements[i].name).addClass('error');
				} else {
					$('#' + theForm.elements[i].name).removeClass('error');
				}
			} else if ($.trim(theForm.elements[i].value) === '' && theForm.elements[i].type === 'text') {
				$('#' + theForm.elements[i].name).addClass('error');
			} else {
				$('#' + theForm.elements[i].name).removeClass('error');
			}
		}
	}

	if (group.hasClass('error') === false) {
		switch (button) {
			case 'update_campaign':
				overlay.height($(document).height()).fadeIn(300);
				ajaxCampaign(false);
				break;
			case 'insert_campaign_add_seminar':
			overlay.height($(document).height()).fadeIn(300);
				ajaxCampaign();
				break;
			case 'update_seminar_info':
				overlay.height($(document).height()).fadeIn(300);
				ajaxSeminar(false);
				break;
			case 'insert_seminar_add_seminar':
				overlay.height($(document).height()).fadeIn(300);
				ajaxSeminar();
				break;
		}
	} else {
		return false;
	}

	return false;
}


function ajaxCampaign(redirect) {
	$.ajax({
		url: "/controllers/campaign-information.php",
		type: "POST",
		data: data,
		processData: false,
		contentType: false,
		success: function(data) {
			console.log(data);
			var json = $.parseJSON(data);
			console.log(json);
			overlay.fadeOut(300);
			if (redirect === false) {
				modal = '';
				modal += '<div id="updateCampaign" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="updateCampaignLabel" aria-hidden="true">';
				modal += '<div class="modal-header">';
				modal += '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>';
				modal += '<h2 id="updateCampaignLabel">Campaign: ' + json.name + '</h2>';
				modal += '</div>';
				modal += '<div class="modal-body">';
				modal += '<p><strong>Success: </strong> You have updated the current record!</p>';
				modal += '</div>';
				modal += '<div class="modal-footer">';
				modal += '<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>';
				modal += '</div>';
				modal += '</div>';

				$(modal).appendTo('body');
				$('#updateCampaign').modal().on('hide', function() {
					location.reload();
				});
			} else {
				window.location.replace(window.location.protocol + '//' + window.location.host + '/seminar-information.php?campaign_id=' + json.campaign_id);
			}
		}
	});
}

function ajaxSeminar(redirect) {
	$.ajax({
		url: "/controllers/seminar-information.php",
		type: "POST",
		data: data,
		processData: false,
		contentType: false,
		success: function(data) {
			console.log(data);
			var json = $.parseJSON(data);
			console.log(json);
			overlay.fadeOut(300);
			if (redirect === false) {
				modal = '';
				modal += '<div id="updateSeminar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="updateSeminarLabel" aria-hidden="true">';
				modal += '<div class="modal-header">';
				modal += '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>';
				modal += '<h2 id="updateSeminarLabel">Seminar Updated</h2>';
				modal += '</div>';
				modal += '<div class="modal-body">';
				modal += '<p><strong>Success: </strong> You have updated the current seminar ' + json.name + '</p>';
				modal += '</div>';
				modal += '<div class="modal-footer">';
				modal += '<button class="btn" onClick="document.location.reload(true)">Close</button>';
				modal += '</div>';
				modal += '</div>';

				$(modal).appendTo('body');
				$('#updateSeminar').modal().on('hide', function() {
					location.reload();
				});
			} else {
				window.location.replace(window.location.protocol + '//' + window.location.host + '/seminar-information.php?campaign_id=' + json.campaign_id);
			}
		}
	});
}

// function ajaxResults(redirect) {
// 	$.ajax({
// 		url: "/controllers/seminar-results.php",
// 		type: "POST",
// 		data: data,
// 		processData: false,
// 		contentType: false,
// 		success: function(data) {
// 			console.log(data);
// 			var json = $.parseJSON(data);
// 			console.log(json);
// 			overlay.fadeOut(300);
// 			if (redirect === false) {
// 				modal = '';
// 				modal += '<div id="updateSeminar" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="updateSeminarLabel" aria-hidden="true">';
// 				modal += '<div class="modal-header">';
// 				modal += '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>';
// 				modal += '<h2 id="updateSeminarLabel">Seminar Updated</h2>';
// 				modal += '</div>';
// 				modal += '<div class="modal-body">';
// 				modal += '<p><strong>Success: </strong> You have updated the current seminar for the campaign: ' + json.name + '</p>';
// 				modal += '</div>';
// 				modal += '<div class="modal-footer">';
// 				modal += '<button class="btn" onClick="document.location.reload(true)">Close</button>';
// 				modal += '</div>';
// 				modal += '</div>';

// 				$(modal).appendTo('body');
// 				$('#updateSeminar').modal().on('hide', function() {
// 					location.reload();
// 				});
// 			} else {
// 				window.location.replace(window.location.protocol + '//' + window.location.host + '/seminar-information.php?campaign_id=' + json.campaign_id);
// 			}
// 		}
// 	});
// }

$.each($('input'), function() {
	if ($(this).data('input') == 'currency') {
		$(this).maskMoney({
			symbol: '$ ',
			allowZero: true
		});
	} else if ($(this).data('input') == 'number') {
		$(this).maskMoney({
			symbol: '',
			precision: 0,
			allowZero: true
		});
	}
});