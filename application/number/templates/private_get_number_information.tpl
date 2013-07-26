{% extends "ajax.tpl" %}
{% set number = component.numbers[0] %}
{% block js %}
    $('.number[number = {{number.id}}] .number-content-content').html(get_hidden_content());
    $('.number[number = {{number.id}}] .number-content-menu li').removeClass('active');
    $('.number[number = {{number.id}}] .get_number_information').parent().addClass('active');
{% endblock js %}
{% block html %}
    {% include '@number/build_number_fio.tpl'%}
{% endblock html %}