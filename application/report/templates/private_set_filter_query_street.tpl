{% extends "ajax.tpl" %}
{% set houses = component.houses %}
{% block js %}
    {% if houses is not empty %}
        $('.filter-select-house').html(get_hidden_content()).attr('disabled', false);
    {% else %}
        $('.filter-select-house').html('<option>Ожидание...</option>').attr('disabled', true);
    {% endif%}
{% endblock js %}
{% block html %}
    <option value="all">Выберите дом...</option>
    {% for house in houses %}
    <option value="{{ house.id }}">дом №{{ house.number }}</option>
    {% endfor %}
{% endblock html %}