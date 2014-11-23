{% extends "dialog.tpl" %}

{% block title %}Диалог добавления работы{% endblock title %}

{% block dialog %}
	<select class="dialog-select-work_id form-control">
		<option value="">Выберите работу...</option>
		{% for work in works %}
		<option value="{{ work.get_id() }}">{{ work.get_name() }}</option>
		{% endfor %}
	</select>
{% endblock dialog %}

{% block buttons %}
	<div class="btn btn-primary add_work">Добавить</div>
{% endblock buttons %}

{% block script %}
	// Добавляет работу в группу
	$('.add_work').click(function(){
		var work_id = $('.dialog-select-work_id').val();
		if(work_id > 0){
			$.get('add_work',{
				workgroup_id: {{ workgroup.get_id() }},
				work_id: work_id
			},function(response){
				$('.dialog').modal('hide');
				$(response).replaceAll('.workgroup[workgroup_id = "{{ workgroup.get_id() }}"] .workgroup-content');
			});
		}
	});
{% endblock script %}