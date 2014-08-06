{% extends "ajax.tpl" %}
{% set number = response.number %}
{% set meter = response.meter %}
{% set date = response.time %}
{% set months = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август',
    'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'] %}
{% block js %}
    $('.number[number = {{ number.get_id() }}] .meter[serial = {{ meter.get_serial() }}][meter = {{ meter.get_id() }}] .meter-data-content').html(get_hidden_content())
{% endblock js %}
{% block html %}
    <ul class="inline">
        <li class="previous">
            <a class="get_meter_data_year" act='{{ response.time|date_modify("-1 year")|date("U")}}'><i class="icon-chevron-left"></i></a>
        </li>
        <li>
            <h3>{{ response.time|date('Y')}}</h3>
        </li>
        {% if response.time|date('Y')!="now"|date('Y') %}
        <li class="next">
            <a class="get_meter_data_year" act='{{ response.time|date_modify("+1 year")|date("U")}}'><i class="icon-chevron-right"></i></a>
        </li>
        {% endif %}
    </ul>
    <div class="meter-data-value">
    {% include '@number/build_meter_data.tpl' %}
    </div>
{% endblock html %}