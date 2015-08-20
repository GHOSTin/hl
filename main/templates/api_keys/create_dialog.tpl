{% extends "dialog.tpl" %}

{% block title %}Диалог создания ключа{% endblock %}

{% block dialog %}
<div class="form-group">
  <label>Название ключа</label>
  <input type="text" class="form-control dialog-input-name" autofocus>
</div>
{% endblock %}

{% block buttons %}
  <div class="btn btn-primary create">Создать</div>
{% endblock %}

{% block script %}
// Создает ключ
$('.create').click(function(){
  $.get('create/',{
    name: $('.dialog-input-name').val()
  },function(response){
    $('.dialog').modal('hide');
    $('.keys').html(response);
  });
});
{% endblock %}