{% extends "dialog.tpl" %}
{% set meter = component.meter %}
{% block title %}Диалог исключения периода{% endblock title %}
{% block dialog %}
	Удалить период "{{ request.GET('period') // 12 }} г {{ request.GET('period') % 12 }} мес" из счетчика?
{% endblock dialog %}
{% block buttons %}
	<div class="btn remove_period">Удалить</div>
{% endblock buttons %}
{% block script %}
	$('.remove_period').click(function(){
		$.get('remove_period',{
			id: {{ request.GET('id') }},
			period: '{{ request.GET('period') }}'
			},function(r){
				$('.dialog').modal('hide');
				init_content(r);
			});
	});
{% endblock script %}