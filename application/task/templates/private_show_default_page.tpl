{% extends "default.tpl" %}
{% block component %}
  <div class="col-sm-12">
    <p>
    <div class="btn-group">
      <a type="button" class="btn btn-default active">Текущие задачи</a>
      <a type="button" class="btn btn-default">Законченные задачи</a>
    </div>
    </p>
    <p>
      <a class="btn btn-default get_dialog_create_task">
        <span class="glyphicon glyphicon-plus"></span>
        Добавить задачу
      </a>
    </p>
  </div>
{% endblock%}
{% block javascript %}
  <script src="/?js=component.js&p=task"></script>
  <script src="/templates/default/js/chosen.jquery.js"></script>
  <script src="/templates/default/js/bootstrap-datepicker.js"></script>
{% endblock%}
{% block css %}
  <link rel="stylesheet" href="/templates/default/css/datepicker.css" >
{% endblock css %}