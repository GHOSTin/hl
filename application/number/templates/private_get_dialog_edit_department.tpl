{% extends "dialog.tpl" %}
{% set house = component.house %}
{% set departments = component.departments %}
{% block title %}Диалог редактирования участка{% endblock title %}
{% block dialog %}
	<select class="dialog-select-department">
		<option value="">Выберите участок</option>
		{% for department in departments %}
		<option value="{{ department.get_id() }}"{%if department.get_id() == house.get_department().get_id()%} selected{% endif %}>{{ department.get_name() }}</option>
		{% endfor %}
	</select>
{% endblock dialog %}
{% block buttons %}
	<div class="btn edit_department">Добавить</div>
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