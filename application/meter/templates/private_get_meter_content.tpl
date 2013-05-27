{% extends "ajax.tpl" %}
{% set meter = component.meters[0] %}
{% block js %}
    $('.meter[meter = {{ meter.id }}]').append(get_hidden_content())
{% endblock js %}
{% block html %}
    <div class="meter_content">
        <ul class="nav nav-pills">
            <li><a class="get_dialog_rename_meter">Переименовать</a></li>
        </ul>
        <div>Разрядность: {{ meter.capacity }}</div>
        <div>Тарифность: {{ meter.rates }}</div>
    </div>
{% endblock html %}