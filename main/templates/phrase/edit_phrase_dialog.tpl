{% extends "dialog.tpl" %}

{% block title %}Изменение фразы{% endblock %}

{% block dialog %}
<div class="form-group">
  <label>Текст фразы</label>
  <textarea class="form-control dialog-phrase" rows="3" autofocus>{{ phrase.get_text() }}</textarea>
</div>
{% endblock %}

{% block buttons %}
  <div class="btn btn-primary save_phrase">Сохранить</div>
{% endblock %}

{% block script %}
  $('.save_phrase').click(function(){
    $.ajax('/workgroups/phrases/{{ phrase.get_id() }}/', {
      type: 'PUT',
      data:{
        text: $('.dialog-phrase').val()
      },
      success: function(response){
        $('.dialog').modal('hide');
        location.reload();
      }
    });
  });
{% endblock %}