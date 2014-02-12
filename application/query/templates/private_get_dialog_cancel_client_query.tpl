{% extends "dialog.tpl" %}
{% block title %}Отклонение заявки{% endblock title %}
{% block dialog %}
	<p>Описание причины отказа, не более 255 символов</p>
	<textarea class="dialog-reason form-control" maxlength="255"></textarea>
{% endblock dialog %}
{% block buttons %}
	<div class="btn btn-default cancel_client_query">Отклонить</div>
{% endblock buttons %}
{% block script %}
	$('.cancel_client_query').click(function(){
		$.get('cancel_client_query',{
			number_id: {{ request.GET('number_id') }},
			time: {{ request.GET('time') }},
			reason: $('.dialog-reason').val()
			},function(r){
				init_content(r);
				$('.dialog').modal('hide');
			});
	});
{% endblock script %}