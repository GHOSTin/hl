{% extends "dialog.tpl" %}

{% block title %}Исключение события{% endblock title %}

{% block dialog %}
Вы действительно хотите удалить <strong>{{ event.get_name() }}</strong> из группы <strong>{{ workgroup.get_name() }}</strong>?
{% endblock dialog %}

{% block buttons %}
	<div class="btn btn-primary exclude_event">Исключить</div>
{% endblock buttons %}

{% block script %}
	// Исключает событие
	$('.exclude_event').click(function(){
		$.get('exclude_event',{
			workgroup_id: {{ workgroup.get_id() }},
			event_id: {{ event.get_id() }}
		},function(response){
			$('.dialog').modal('hide');
			$(response).replaceAll('.workgroup[workgroup_id = "{{ workgroup.get_id() }}"] .workgroup-content');
		});
	});
{% endblock script %}