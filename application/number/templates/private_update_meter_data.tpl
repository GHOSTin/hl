{% extends "ajax.tpl" %}
{% set n2m = component.n2m %}
{% set date = component.time %}
{% set months = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август',
'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'] %}
{% block js %}
    $('.number[number = {{ n2m.get_number().get_id() }}] .meter[serial = {{ n2m.get_serial() }}][meter = {{ n2m.get_meter().get_id() }}] .meter-data-value').html(get_hidden_content());
{% endblock js %}
{% block html %}
    {% include '@number/build_meter_data.tpl' %}
{% endblock html %}