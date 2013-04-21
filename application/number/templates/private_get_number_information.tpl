{% extends "ajax.tpl" %}
{% block js %}
    $('.number[number = {{component.id}}] .number-content').html(get_hidden_content())
{% endblock js %}
{% block html %}
    {% include '@number/build_number_information.tpl' %}
{% endblock html %}