{% extends "dialog.tpl" %}

{% block title %}Диалог создания работы{% endblock title %}

{% block dialog %}
<div class="form-group">
	<input type="text" class="form-control dialog-input-name">
</div>
{% endblock dialog %}

{% block buttons %}
	<div class="btn btn-primary create_work">Создать</div>
{% endblock buttons %}

{% block script %}
	// Создает работу
	$('.create_work').click(function(){
		$.get('create_work',{
			name: $('.dialog-input-name').val()
		},function(response){
			$('.dialog').modal('hide');
			$('.works').html(response);
		});
	});
{% endblock script %}