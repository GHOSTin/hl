{% extends "ajax.tpl" %}
{% block js %}
    $('.import-form').html(get_hidden_content());
{% endblock js %}
{% block html %}
    Дом "№{{ component.house.get_number() }}" успешно залит.
{% endblock html %}