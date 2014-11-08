{% extends "dialog.tpl" %}

{% block title %}Диалог создания группы работ{% endblock title %}

{% block dialog %}
<div class="form-group">
	<input type="text" class="form-control dialog-input-name">
</div>
{% endblock dialog %}

{% block buttons %}
	<div class="btn btn-primary create_workgroup">Создать</div>
{% endblock buttons %}

{% block script %}
	// Создает группу работ
	$('.create_workgroup').click(function(){
		$.get('create_workgroup',{
			name: $('.dialog-input-name').val()
			},function(response){
				$('.dialog').modal('hide');
				$('.workgroups').html(response);
			});
	});
{% endblock script %}