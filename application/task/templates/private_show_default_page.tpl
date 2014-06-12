{% extends "default.tpl" %}
{% block component %}
  <div>
    <p>
    <div class="btn-group">
      <a type="button" class="btn btn-default active" id="get_active_tasks">Текущие задачи</a>
      <a type="button" class="btn btn-default get_finished_tasks">Законченные задачи</a>
    </div>
    </p>
    <p>
      <a class="btn btn-success get_dialog_create_task">
        <span class="glyphicon glyphicon-plus"></span>
        Добавить задачу
      </a>
    </p>
  </div>
  <div class="row">
    <div id="task_container" class="col-xs-12 col-sm-5 col-lg-4"></div>
  </div>
{% endblock%}
{% block javascript %}
  <script src="/?js=component.js&p=task"></script>
  <script src="/templates/default/js/chosen.jquery.js"></script>
  <script src="/templates/default/js/bootstrap-datepicker.js"></script>
{% endblock%}
{% block css %}
  <link rel="stylesheet" href="/?css=component.css&p=task">
  <link rel="stylesheet" href="/templates/default/css/datepicker.css" >
{% endblock css %}