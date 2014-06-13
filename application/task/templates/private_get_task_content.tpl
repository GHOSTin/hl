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
          <button type="button" class="btn btn-default navbar-btn pull-left visible-xs visible-sm">
            <i class="glyphicon glyphicon-chevron-left"></i>
          </button>
          {% if task.get_creator().get_id() == user.get_id() %}
          <form class="navbar-form pull-right">
            <button type="button" class="btn btn-default" id="task_edit">
              <i class="glyphicon glyphicon-edit"></i><span class="hidden-xs hidden-sm">Редактировать</span>
            </button>
          </form>
          {% endif %}
          <p class="navbar-text">
            <span class="visible-xs visible-sm text-center">{{ open_date }} - {{ close_date }}</span>
            <span class="hidden-xs hidden-sm">Выполняется с {{ open_date }} по {{ close_date }}</span>
          </p>
        </div>
      </div>
    </nav>
    <div class="col-xs-12">
      <p id="task_description">{{ task.get_description()|nl2br }}</p>
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
  $('#task_content').find('section').html(get_hidden_content());
{% endblock %}
