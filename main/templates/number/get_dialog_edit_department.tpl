{% extends "dialog.tpl" %}

{% block title %}Диалог привязки участка{% endblock %}

{% block dialog %}
	<div class="form-group">
		<select class="dialog-select-department form-control">
			<option value="">Выберите участок</option>
			{% for department in departments %}
			<option value="{{ department.get_id() }}"{%if department.get_id() == house.get_department().get_id()%} selected{% endif %}>{{ department.get_name() }}</option>
			{% endfor %}
		</select>
	</div>
{% endblock %}

{% block buttons %}
	<div class="btn btn-primary edit_department">Сохранить</div>
{% endblock %}

{% block script %}
// Добавляет идентификатор в процессинговом центре
$('.edit_department').click(function(){
	$.get('edit_department',{
		house_id: {{ house.get_id() }},
		department_id: $('.dialog-select-department').val(),
	},function(response){
		$('.dialog').modal('hide');
		$('.workspace').html(response)
	});
});
{% endblock %}