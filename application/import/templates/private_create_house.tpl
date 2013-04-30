{% extends "ajax.tpl" %}
{% block js %}
    $('.import-form').html(get_hidden_content());
{% endblock js %}
{% block html %}
    Дом "№{{ component.number }}" успешно залит.
{% endblock html %}