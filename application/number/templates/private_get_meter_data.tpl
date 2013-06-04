{% extends "ajax.tpl" %}
{% set meter = component.meters[0] %}
{% set number = component.number %}
{% set date = component.time %}
{% set months = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август',
    'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'] %}
{% block js %}
    $('.number[number = {{ number.id }}] .meter[serial = {{ meter.serial }}][meter = {{ meter.id }}]').append(get_hidden_content())
{% endblock js %}
{% block html %}
<div class="meter-data">
    <ul class="pager">
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
    {% for i in 0..11 %}
        {% set data = component.meter_data[date|date("U")] %}
        <div class="month row" time="{{ date|date("U") }}">
            <div class="span2">{{ months[i] }}</div>
            <div class="span2"></div>
            {% if meter.rates == 2 %}
            <div class="span2"></div>
            {% endif %}
            {% if meter.rates == 3 %}
            <div class="span2"></div>
            <div class="span2"></div>
            {% endif %}
            <div class="span2"><a class="btn get_dialog_edit_meter_data">изменить</a></div>
        </div>
        {% set date = date|date_modify("+1 month") %}
    {% endfor %}
    </div>
    </table>
</div>
{% endblock html %}