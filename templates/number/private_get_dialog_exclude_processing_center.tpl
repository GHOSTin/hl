{% extends "dialog.tpl" %}
{% set center = response.center %}
{% block title %}Диалог исключения процессингового центра{% endblock title %}
{% block dialog %}
	Вы действительно хотите удалить идентификатор <b>{{ request.GET('identifier') }}</b> расчетного центра <b>{{ center.get_name() }}</b>?
{% endblock dialog %}
{% block buttons %}
	<div class="btn btn-primary exclude_processing_center">Удалить</div>
{% endblock buttons %}
{% block script %}
// Удаляет идентификатор в процессинговом центре
$('.exclude_processing_center').click(function(){
	$.get('exclude_processing_center',{
		number_id: {{ request.GET('number_id') }},
		center_id: {{ center.get_id() }},
		},function(r){
			$('.dialog').modal('hide');
			init_content(r);
		});
});
{% endblock script %}