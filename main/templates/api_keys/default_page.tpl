{% extends "default.tpl" %}

{% block component %}
<div class="row">
  <div class="col-md-2">
    <ul class="nav nav-pills menu">
      <li>
        <a href="/system/">Вернуться к меню системы</a>
      </li>
    </ul>
  </div>
  <div class="col-md-10">
    <a class="get_dialog_create_api_key btn btn-default">Создать</a>
    <ul class="keys list-unstyled">{% include 'api_keys/keys.tpl' %}</ul>
  </div>
</div>
{% endblock %}

{% block javascript %}
<script>
  $(document).ready(function(){
  $('body').on('click', '.get_dialog_create_api_key', function(){
    $.get('create/dialog/',{
    },function(r){
      init_content(r);
    });
  });
});
</script>
{% endblock %}