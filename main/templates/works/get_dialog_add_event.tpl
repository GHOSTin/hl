{% extends "dialog.tpl" %}

{% block title %}Добавление события{% endblock title %}

{% block dialog %}
	<select class="dialog-select-event_id form-control">
		<option value="">Выберите событие...</option>
		{% for event in events %}
		<option value="{{ event.get_id() }}">{{ event.get_name() }}</option>
		{% endfor %}
	</select>
{% endblock dialog %}

{% block buttons %}
	<div class="btn btn-primary add_event">Добавить</div>
{% endblock buttons %}

{% block script %}
	// Добавляет работу в группу
	$('.add_event').click(function(){
		var event_id = $('.dialog-select-event_id').val();
		if(event_id > 0){
			$.get('add_event',{
				workgroup_id: {{ workgroup.get_id() }},
				event_id: event_id
			},function(response){
				$('.dialog').modal('hide');
				$(response).replaceAll('.workgroup[workgroup_id = "{{ workgroup.get_id() }}"] .workgroup-content');
			});
		}
	});
{% endblock script %}