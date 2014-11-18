{% extends "dialog.tpl" %}
{% set house = response.house %}
{% set departments = response.departments %}
{% block title %}Диалог редактирования участка{% endblock title %}
{% block dialog %}
	<div class="form-group">
		<select class="dialog-select-department form-control">
			<option value="">Выберите участок</option>
			{% for department in departments %}
			<option value="{{ department.get_id() }}"{%if department.get_id() == house.get_department().get_id()%} selected{% endif %}>{{ department.get_name() }}</option>
			{% endfor %}
		</select>
	</div>
{% endblock dialog %}
{% block buttons %}
	<div class="btn btn-default edit_department">Изменить</div>
{% endblock buttons %}
{% block script %}
// Добавляет идентификатор в процессинговом центре
$('.edit_department').click(function(){
	$.get('edit_department',{
		house_id: {{ house.get_id() }},
		department_id: $('.dialog-select-department').val(),
		},function(r){
			$('.dialog').modal('hide');
			init_content(r);
		});
});
{% endblock script %}