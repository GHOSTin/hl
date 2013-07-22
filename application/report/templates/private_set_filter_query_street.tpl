{% extends "ajax.tpl" %}
{% set houses = component.houses %}
{% block js %}
    $('.filter-select-house').html(get_hidden_content());
{% endblock js %}
{% block html %}
    <option value="all">Выберите дом...</option>
    {% for house in houses %}
    <option value="{{ house.id }}">дом №{{ house.number }}</option>
    {% endfor %}
{% endblock html %}