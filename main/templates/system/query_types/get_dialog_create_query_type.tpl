{% extends "dialog.tpl" %}

{% block title %}Диалог создания типа заявки{% endblock title %}

{% block dialog %}
<div class="form-group">
	<input type="text" class="form-control dialog-input-name">
</div>
{% endblock dialog %}

{% block buttons %}
	<div class="btn btn-primary create_query_type">Создать</div>
{% endblock buttons %}

{% block script %}
	// Создает работу
	$('.create_query_type').click(function(){
		$.get('create_query_type',{
			name: $('.dialog-input-name').val()
		},function(response){
			$('.dialog').modal('hide');
			$('.query_types').html(response);
		});
	});
{% endblock script %}