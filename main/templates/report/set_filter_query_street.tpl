{% extends "ajax.tpl" %}

{% block js %}
    {% if street.get_houses() is not empty %}
        $('.filter-select-house').html(get_hidden_content()).attr('disabled', false);
    {% else %}
        $('.filter-select-house').html('<option>Ожидание...</option>').attr('disabled', true);
    {% endif%}
{% endblock js %}

{% block html %}
    <option value="all">Выберите дом...</option>
    {% for house in street.get_houses() %}
    <option value="{{ house.get_id() }}">дом №{{ house.get_number() }}</option>
    {% endfor %}
{% endblock html %}