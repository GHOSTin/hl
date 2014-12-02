{% extends "dialog.tpl" %}

{% block title %}Диалог переименования работы{% endblock title %}

{% block dialog %}
<div class="form-group">
	<input type="text" class="form-control dialog-input-name" value="{{ work.get_name() }}">
</div>
{% endblock dialog %}

{% block buttons %}
	<div class="btn btn-primary rename_work">Переименовать</div>
{% endblock buttons %}

{% block script %}
	// Переименовывает работу
	$('.rename_work').click(function(){
		$.get('rename_work',{
			id: {{ work.get_id() }},
			name: $('.dialog-input-name').val()
		},function(response){
			$('.dialog').modal('hide');
			$('.work[work_id = "{{ work.get_id() }}"] .work-title').html(response);
		});
	});
{% endblock script %}