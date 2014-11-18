{% extends "default.tpl" %}
{% block component %}
  <div>
    <p>
    <div class="btn-group" data-toggle="buttons">
      <label class="btn btn-default" id="get_active_tasks"><input type="radio" name="options" id="option1">Текущие задачи</label>
      <label class="btn btn-default" id="get_finished_tasks"><input type="radio" name="options" id="option2">Законченные задачи</label>
    </div>
    </p>
  </div>
  <div class="row">
    <div id="task_container" class="col-xs-12 col-sm-5 col-lg-4"></div>
    <div id="task_content" class="col-xs-12 col-sm-7 col-lg-8">
      <section></section>
    </div>
  </div>
{% endblock%}
{% block javascript %}
  <script src="/js/task.js"></script>
  <script src="/js/chosen.jquery.js"></script>
  <script src="/js/bootstrap-datepicker.js"></script>
{% endblock%}
{% block css %}
  <link rel="stylesheet" href="/css/task.css">
  <link rel="stylesheet" href="/css/datepicker.css" >
{% endblock css %}