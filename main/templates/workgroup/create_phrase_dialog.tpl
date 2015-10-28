{% extends "dialog.tpl" %}

{% block title %}Добавление фразы в <u>{{ workgroup.get_name() }}</u>{% endblock %}

{% block dialog %}
<div class="form-group">
  <label>Текст фразы</label>
  <textarea class="form-control dialog-phrase" rows="3" autofocus></textarea>
</div>
{% endblock %}

{% block buttons %}
  <div class="btn btn-primary add_phrase">Добавить</div>
{% endblock %}

{% block script %}
  $('.add_phrase').click(function(){
    $.post('/workgroups/{{ workgroup.get_id() }}/phrases/',{
      phrase: $('.dialog-phrase').val()
    },function(response){
      $('.dialog').modal('hide');
      $(response).replaceAll('.workgroup[workgroup_id = "{{ workgroup.get_id() }}"] .workgroup-content');
    });
  });
{% endblock %}