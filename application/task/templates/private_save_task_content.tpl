{% extends "ajax.tpl" %}
{% set task = component.task %}
{% block html %}
  {% include '@task/task_content.tpl' with {'task': task} %}
{% endblock %}
{% block js %}
  $('#task_content').find('section').html(get_hidden_content());

  $('a[href^="#{{ task.get_id() }}"]')
    .replaceWith('{{ include('@task/short_task_content.tpl', {'task': task})|replace({"\n":""})|trim|raw }}');
{% endblock %}