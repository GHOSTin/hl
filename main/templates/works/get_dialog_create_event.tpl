{% extends "dialog.tpl" %}

{% block title %}Диалог создания события{% endblock title %}

{% block dialog %}
<div class="form-group">
	<input type="text" class="form-control dialog-input-name">
</div>
{% endblock dialog %}

{% block buttons %}
	<div class="btn btn-primary create_event">Создать</div>
{% endblock buttons %}

{% block script %}
	// Создает группу работ
	$('.create_event').click(function(){
		$.get('create_event',{
			name: $('.dialog-input-name').val()
		},function(response){
			$('.dialog').modal('hide');
			$('.events').html(response);
		});
	});
{% endblock script %}