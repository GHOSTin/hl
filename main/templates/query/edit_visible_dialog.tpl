{% extends "dialog.tpl" %}

{% block title %}Изменение видимости{% endblock %}

{% block dialog %}
  {% if query.is_visible() %}
  Скрыть заявку в личном кабинете?
  {% else %}
  Показать заявку в личном кабинете?
  {% endif %}
{% endblock %}

{% block buttons %}
  <div class="btn btn-default update_visible">Изменить</div>
{% endblock %}

{% block script %}
  $('.update_visible').click(function(){
    $.get('/queries/{{ query.get_id }}/visible/'
    ,function(r){
      init_content(r);
      $('.dialog').modal('hide');
    });
  });
{% endblock %}