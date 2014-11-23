{% extends "ajax.tpl" %}
{% set tasks = response.tasks %}
{% block html %}
  <div class="list-group">
    {% for task in tasks %}
      {% include '@task/short_task_content.tpl' with {'task': task} %}
    {% endfor %}
  </div>
{% endblock %}
{% block js %}
  $('#task_container').html(get_hidden_content());
{% endblock %}
