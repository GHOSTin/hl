{% extends "dialog.tpl" %}

{% set workgroup = response.workgroup %}

{% block title %}Диалог переименования группы работ{% endblock title %}

{% block dialog %}
<div class="form-group">
	<input type="text" class="form-control dialog-input-name" value="{{ workgroup.get_name() }}">
</div>
{% endblock dialog %}

{% block buttons %}
	<div class="btn btn-primary rename_workgroup">Переименовать</div>
{% endblock buttons %}

{% block script %}
	// Переименовывает группу работ
	$('.rename_workgroup').click(function(){
		$.get('rename_workgroup',{
			workgroup_id: {{ workgroup.get_id() }},
			name: $('.dialog-input-name').val()
			},function(response){
				$('.dialog').modal('hide');
				$('.workgroup[workgroup_id = "{{ workgroup.get_id() }}"] .workgroup-title').html(response);
			});
	});
{% endblock script %}