{% extends "ajax.tpl" %}
{% set meter = component.meter %}
{% block html %}
    <option value="0">Выберите период</option>
   {% for period in meter.get_periods() %}
    <option value="{{ period }}">{{ period }}</option>
   {% endfor %}
{% endblock html %}
{% block js %}
    $('.dialog-select-periods').html(get_hidden_content());
    $('.dialog-select-periods').prop('disabled', false);
{% endblock js %}