{% extends "ajax.tpl" %}
{% set number = component.numbers[0] %}
{% block js %}
    $('.number[number = {{number.id}}] .number-content').html(get_hidden_content())
{% endblock js %}
{% block html %}
    {% include '@number/build_number_information.tpl' %}
{% endblock html %}