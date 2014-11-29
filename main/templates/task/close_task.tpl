{% embed  'task/show_active_tasks.tpl' %}
  {% block js %}
    window.location = '#';
    $('#task_container').html(get_hidden_content());
  {% endblock %}
{% endembed %}
