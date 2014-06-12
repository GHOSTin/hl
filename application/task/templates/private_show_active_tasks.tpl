{% extends "ajax.tpl" %}
{% set tasks = component.tasks %}
{% block html %}
  <div class="list-group">
    {% for task in tasks %}
      {% set days = ((task.get_time_close() - "now"|date("U"))/86400)|round(0, 'floor')|abs %}
      {%  if task.get_time_close() > "now"|date("U")%}
        {% set number = "+" %}
        {% set label = 'success' %}
      {% else %}
        {% set number = "-" %}
        {% set label = 'danger' %}
      {% endif %}
    <a href="#" class="list-group-item">
      <div class="media">
        <div class="pull-left">
          <span class="label label-{{ label }} media-object task-media">{{ number }}{{ days }}</span>
        </div>
        <div class="media-body">
          <h5 class="list-group-item-heading media-heading"><strong>{{ task.get_description() }}</strong></h5>
          {% set creator = task.get_creator() %}
          <p class="list-group-item-text">
            <i class="glyphicon glyphicon-user" style="font-size: 20px;"></i>
            {{ creator.get_lastname()}} {{ creator.get_firstname()|first|upper }}.{{ creator.get_middlename()|first|upper }}.</p>
        </div>
      </div>
    </a>
    {% endfor %}
  </div>
{% endblock %}
{% block js %}
  $('#task_container').html(get_hidden_content());
{% endblock %}
