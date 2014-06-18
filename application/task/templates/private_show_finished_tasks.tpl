{% extends "ajax.tpl" %}
{% set tasks = component.tasks %}
{% block html %}
  <div class="list-group">
    {% for task in tasks %}
    <a href="#{{ task.get_id() }}/" class="list-group-item" data-ajax="true">
      <div class="media">
        <div class="pull-left">
          <span class="label label-success media-object task-media">
            <i class="glyphicon glyphicon-lock"></i>
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
{% endblock %}
{% block js %}
  $('#task_container').html(get_hidden_content());
{% endblock %}
