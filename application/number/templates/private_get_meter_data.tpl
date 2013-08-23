{% extends "ajax.tpl" %}
{% set meter = component.meter %}
{% set date = component.time %}
{% set months = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август',
    'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'] %}
{% block js %}
    $('.number[number = {{ meter.get_number_id() }}] .meter[serial = {{ meter.get_serial() }}][meter = {{ meter.get_meter_id() }}]').append(get_hidden_content())
{% endblock js %}
{% block html %}
<div class="meter-data">
    <div>
        <ul class="nav nav-pills" style="padding:20px 0px">
            <li><a class="get_meter_value">Показания</a></li>
            <li><a class="get_meter_info">Информация</a></li>
            <li><a class="get_meter_docs">Документы</a></li>
        <ul>
    </div>
    <div class="meter-data-content">
        <ul class="list-inline text-center">
            <li class="previous">
                <a class="btn btn-default get_meter_data_year" act='{{ component.time|date_modify("-1 year")|date("U")}}'><i class="glyphicon glyphicon-chevron-left"></i></a>
            </li>
            <li>
                <h3>{{ component.time|date('Y')}}</h3>
            </li>
            <li class="next">
                <a class="btn btn-default get_meter_data_year" act='{{ component.time|date_modify("+1 year")|date("U")}}'
                {% if component.time|date('Y')=="now"|date('Y') %}
                    disabled
                {% endif %}
                ><i class="glyphicon glyphicon-chevron-right"></i></a>
            </li>
        </ul>
        <div class="meter-data-value">
        {% include '@number/build_meter_data.tpl' %}
        </div>
    </div>
</div>
{% endblock html %}