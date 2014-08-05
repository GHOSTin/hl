{% extends "dialog.tpl" %}
{% set user = component.user %}
{% set companies = component.companies %}
{% block title %}Диалог добавления нового профиля{% endblock title %}
{% block dialog %}
	<select class="dialog-select-company form-control">
		<option value="">Выберите компанию...</option>
		{% for company in companies %}
		<option value="{{ company.get_id() }}">{{ company.get_name() }}</option>
		{% endfor %}
	</select>
    <select class="dialog-select-profile form-control">
        <option value="">Выберите профиль...</option>
        <option value="query">Заявки</option>
        <option value="number">Жилой фонд</option>
        <option value="user">Управление пользователями</option>
        <option value="report">Отчеты</option>
    </select>
{% endblock dialog %}
{% block buttons %}
	<div class="btn btn-primary add_profile">Добавить</div>
{% endblock buttons %}
{% block script %}
	// Добавляет новый профиль
	$('.add_profile').click(function(){
		var company = $('.dialog-select-company').val();
		if(company > 0){
			$.get('add_profile',{
				company_id: company,
				user_id: {{ request.take_get('id') }},
				profile: $('.dialog-select-profile').val()
				},function(r){
					$('.dialog').modal('hide');
					init_content(r);
				});
		}
	});
{% endblock script %}