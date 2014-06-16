{% extends "ajax.tpl" %}
{% set tasks = component.tasks %}
{% set task = component.task %}
{% set creator = task.get_creator() %}
{% set open_date = task.get_time_open()|date('d.m.Y') %}
{% set close_date = task.get_time_close()|date('d.m.Y') %}
{% block html %}
<div id="task_container" class="col-xs-12 col-sm-5 col-lg-4">
  <div class="list-group">
    {% for task in tasks %}
      {% set days = ((task.get_time_close() - "now"|date("U"))/86400)|round(0, 'floor')|abs %}
      {% if days == 0 %}
        {% set hours = ((task.get_time_close() - "now"|date("U"))/3600)|round(0, 'floor')|abs %}
      {% endif %}
      {%  if task.get_time_close() > "now"|date("U")%}
        {% set number = "+" %}
        {% set label = 'success' %}
      {% else %}
        {% set number = "-" %}
        {% set label = 'danger' %}
      {% endif %}
      <a href="#{{ task.get_id() }}/" class="list-group-item" data-ajax="true">
        <div class="media">
          <div class="pull-left">
          <span class="label label-{{ label }} media-object task-media">
            {{ number }}{% if days != 0 %}{{ days }}<small>дней</small>
            {% else %}{{ hours }}<small>часов</small>{% endif %}
          </span>
          </div>
          <div class="media-body">
            <h5 class="list-group-item-heading media-heading"><strong>{{ task.get_description()|nl2br }}</strong></h5>
            {% set creator = task.get_creator() %}
            <p class="list-group-item-text">
              <i class="glyphicon glyphicon-user" style="font-size: 20px;"></i>
              {{ creator.get_lastname()}} {{ creator.get_firstname()|first|upper }}.{{ creator.get_middlename()|first|upper }}.</p>
          </div>
        </div>
      </a>
    {% endfor %}
  </div>
</div>
<div id="task_content" class="col-xs-12 col-sm-7 col-lg-8">
  <section>
    <div class="row" id="task" data-id="{{ task.get_id() }}">
      <nav class="navbar navbar-default navbar-static-top" role="navigation">
        <div class="container-fluid">
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
  </section>
</div>
{% endblock %}
{% block js %}
  $('#task_container').parent().html(get_hidden_content());
{% endblock %}