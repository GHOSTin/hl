{% extends "ajax.tpl" %}
{% set old_meter = component.old_meter %}
{% set meter = component.new_meters[0] %}
{% set services = {'cold_water':'Холодное водоснабжение',
    'hot_water':'Горячее водоснабжение', 'electrical':'Электроэнергия'} %}
{% set rates = ['однотарифный', 'двухтарифный', 'трехтарифный'] %}
{% block js %}
    $('.number[number = {{ old_meter.number_id }}] .meter[serial = {{ old_meter.serial }}][meter = {{ old_meter.meter_id }}] .meter-data-content').html(get_hidden_content());
    $('.number[number = {{ old_meter.number_id }}] .meter[serial = {{ old_meter.serial }}][meter = {{ old_meter.meter_id }}] .get_meter_data').html('{{ services[meter.service] }} {{ meter.name }} №{{ meter.serial }} ({{ meter.date_next_checking|date('d.m.Y') }})');
    $('.number[number = {{ old_meter.number_id }}] .meter[serial = {{ old_meter.serial }}][meter = {{ old_meter.meter_id }}]').attr('serial', '{{ meter.serial }}');
{% endblock js %}
{% block html %}
    <ul>
        <li>Услуга: {{ services[meter.service] }}</li>
        <li>Название: {{ meter.name }} <a>заменить счетчик</a></li>
        <li>Серийный номер: {{ meter.serial }} <a class="get_dialog_edit_serial">изменить</a></li>
        <li>Тарифность: {{ rates[meter.rates - 1] }}</li>
        <li>Разрядность: {{ meter.capacity }}</li>
        <li>Дата производства: {{ meter.date_release|date('d.m.Y') }} <a class="get_dialog_edit_serial">изменить</a></li>
        <li>Дата установки: {{ meter.date_install|date('d.m.Y') }} <a class="get_dialog_edit_serial">изменить</a></li>
        <li>Дата последней поверки: {{ meter.date_checking|date('d.m.Y') }} <a class="get_dialog_edit_serial">изменить</a></li>
        <li>Период: {{ meter.period }} <a class="get_dialog_edit_serial">изменить</a></li>
        <li>Дата следующей поверки: {{ meter.date_next_checking|date('d.m.Y') }}</li>
    </ul>
{% endblock html %}