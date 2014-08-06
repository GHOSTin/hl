{% extends "ajax.tpl" %}
{% set tasks = response.tasks %}
{% block html %}
  <p>
    <a class="btn btn-success get_dialog_create_task">
      <span class="glyphicon glyphicon-plus"></span>
      Добавить задачу
    </a>
  </p>
  <div class="list-group">
    {% for task in tasks %}
      {% include '@task/short_task_content.tpl' with {'task': task} %}
    {% endfor %}
  </div>
{% endblock %}
{% block js %}
  $('#task_container').html(get_hidden_content());
{% endblock %}
