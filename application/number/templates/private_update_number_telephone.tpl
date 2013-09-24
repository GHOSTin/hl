{% extends "ajax.tpl" %}
{% set number = component.number %}
{% block js %}
    $('.number[number = {{ number.get_id() }}] .number-content-content').html(get_hidden_content());
{% endblock js %}
{% block html %}
    {% include '@number/build_number_fio.tpl' %}
{% endblock html %}