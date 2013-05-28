{% extends "ajax.tpl" %}
{% set meters = component.meters %}
{% block html %}
    <option value="0">Выберите счетчик</option>
   {% for meter in meters %}
    <option value="{{ meter.id }}">{{ meter.name }}</option>
   {% endfor %}
{% endblock html %}
{% block js %}
    $('.dialog-select-meters').html(get_hidden_content());
    $('.dialog-select-meters').prop('disabled', false);
{% endblock js %}