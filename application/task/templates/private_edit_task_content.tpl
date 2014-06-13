{% extends "ajax.tpl" %}
{% set task = component.task %}
{% set creator = task.get_creator() %}
{% set open_date = task.get_time_open()|date('d.m.Y') %}
{% set close_date = task.get_time_close()|date('d.m.Y') %}
{% block html %}
  <div class="row">
    <nav class="navbar navbar-default navbar-static-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <form class="navbar-form pull-right">
            <input type="text" class="form-control" id="task_time_close" value="{{ close_date }}">
            <button type="button" class="btn btn-default" id="task_edit">
              <i class="glyphicon glyphicon-save"></i><span class="hidden-xs hidden-sm">Сохранить</span>
            </button>
          </form>
          <p class="navbar-text">
            <span class="visible-xs visible-sm text-center">{{ open_date }} -</span>
            <span class="hidden-xs hidden-sm">
              Выполняется с {{ open_date }} по
            </span>
          </p>
        </div>
      </div>
    </nav>
    <div class="col-xs-12">
      <textarea type="text" name="description" id="task-description"
                class="form-control" placeholder="Что нужно сделать" rows="5">
        {{ task.get_description() }}
      </textarea>
      <p>
      <ul class="list-group">
        <li class="list-group-item list-group-item-info">
          Постановщик:
          <strong>
            {{ creator.get_lastname()}} {{ creator.get_firstname()|first|upper }}.{{ creator.get_middlename()|first|upper }}.
          </strong>
        </li>
        <li class="list-group-item list-group-item-info">
          Исполнители:
          <strong>
            {% for performer in task.get_performers() %}
              <span class="task_performer">
                {{ performer.get_lastname()}} {{ performer.get_firstname()|first|upper }}.{{ performer.get_middlename()|first|upper }}.{% if loop.revindex > 1 %}, {% endif%}
              </span>
            {% endfor %}
          </strong>
        </li>
      </ul>
      </p>
    </div>
  </div>
{% endblock %}
{% block js %}
  $('#task_time_close').datepicker({
  format: "dd.mm.yyyy",
  weekStart: 1,
  todayBtn: "linked",
  language: "ru",
  autoclose: true,
  todayHighlight: true
  });
  $('#task_content').find('section').html(get_hidden_content());
{% endblock %}