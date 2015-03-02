{% extends "dialog.tpl" %}

{% block title %}Переименование события{% endblock title %}

{% block dialog %}
<div class="form-group">
	<input type="text" class="form-control dialog-input-name" value="{{ event.get_name() }}">
</div>
{% endblock dialog %}

{% block buttons %}
	<div class="btn btn-primary rename_event">Переименовать</div>
{% endblock buttons %}

{% block script %}
	// Переименовывает работу
	$('.rename_event').click(function(){
		$.get('rename_event',{
			id: {{ event.get_id() }},
			name: $('.dialog-input-name').val()
		},function(response){
			$('.dialog').modal('hide');
			$('.event[event_id = "{{ event.get_id() }}"] .event-title').html(response);
		});
	});
{% endblock script %}