{% extends "dialog.tpl" %}
{% set meter = component.meter %}
{% block title %}Диалог исключения периода{% endblock title %}
{% block dialog %}
	Удалить период "{{ meter.periods[0] // 12 }} г {{ meter.periods[0] % 12 }} мес" из счетчика?
{% endblock dialog %}
{% block buttons %}
	<div class="btn remove_period">Удалить</div>
{% endblock buttons %}
{% block script %}
	$('.remove_period').click(function(){
		$.get('remove_period',{
			id: {{ meter.id }},
			period: '{{ meter.periods[0] }}'
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}