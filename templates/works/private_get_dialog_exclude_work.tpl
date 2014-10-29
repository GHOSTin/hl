{% extends "dialog.tpl" %}

{% set work = response.work %}
{% set workgroup = response.workgroup %}

{% block title %}Диалог исключения работы{% endblock title %}

{% block dialog %}
Вы действительно хотите удалить <strong>{{ work.get_name() }}</strong> из группы <strong>{{ workgroup.get_name() }}</strong>?
{% endblock dialog %}

{% block buttons %}
	<div class="btn btn-primary exclude_work">Исключить</div>
{% endblock buttons %}

{% block script %}
	// Исключает работу из группу
	$('.exclude_work').click(function(){
		$.get('exclude_work',{
			workgroup_id: {{ workgroup.get_id() }},
			work_id: {{ work.get_id() }}
			},function(response){
				$('.dialog').modal('hide');
				$(response).replaceAll('.workgroup[workgroup_id = "{{ workgroup.get_id() }}"] .workgroup-content');
			});
	});
{% endblock script %}