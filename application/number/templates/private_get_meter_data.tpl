{% extends "ajax.tpl" %}
{% set meter = component.meters[0] %}
{% set date = component.time %}
{% set months = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август',
    'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'] %}
{% block js %}
    $('.number[number = {{ meter.number_id }}] .meter[serial = {{ meter.serial }}][meter = {{ meter.meter_id }}]').append(get_hidden_content())
{% endblock js %}
{% block html %}
<div class="meter-data">
    <div>
        <ul class="nav nav-pills" style="padding:20px 0px">
            <li><a class="get_meter_value">Показания</a></li>
            <li><a class="get_meter_info">Информация</a></li>
        <ul>
    </div>
    <div class="meter-data-content">
        <ul class="inline">
            <li class="previous">
                <a class="get_meter_data_year" act='{{ component.time|date_modify("-1 year")|date("U")}}'><i class="icon-chevron-left"></i></a>
            </li>
            <li>
                <h3>{{ component.time|date('Y')}}</h3>
            </li>
            {% if component.time|date('Y')!="now"|date('Y') %}
            <li class="next">
                <a class="get_meter_data_year" act='{{ component.time|date_modify("+1 year")|date("U")}}'><i class="icon-chevron-right"></i></a>
            </li>
            {% endif %}
        </ul>
        <div class="meter-data-value">
        {% include '@number/build_meter_data.tpl' %}
        </div>
    </div>
</div>
{% endblock html %}