{% extends "dialog.tpl" %}

{% block title %}Удаление фразы{% endblock %}

{% block dialog %}
Удалить фразу "{{ phrase.get_text() }}"?
{% endblock %}

{% block buttons %}
  <div class="btn btn-primary remove_phrase">Удалить</div>
{% endblock %}

{% block script %}
  $('.remove_phrase').click(function(){
    $.ajax('/workgroups/phrases/', {
      type: 'DELETE',
      data:{
        id: {{ phrase.get_id() }}
      },
      success: function(response){
        $('.dialog').modal('hide');
        $('.phrase[phrase = "{{ phrase.get_id() }}"]').remove();
      }
    });
  });
{% endblock %}