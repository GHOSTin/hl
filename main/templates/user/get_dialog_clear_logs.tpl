{% extends "dialog.tpl" %}

{% block title %}Диалог очистки логов{% endblock title %}

{% block dialog %}
	Вы действительно хотите удалить все логи?
{% endblock dialog %}

{% block buttons %}
	<div class="btn btn-primary clear_logs">Очистить</div>
{% endblock buttons %}

{% block script %}
  // Изменяет пароля пользователя
  $('.clear_logs').click(function(){
    $.get('clear_logs',{
      },function(r){
        $('.dialog').modal('hide');
        init_content(r);
    });
  });
{% endblock script %}