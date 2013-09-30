{% extends "ajax.tpl" %}
{% set n2m = component.n2m %}
{% set date = component.time %}
{% set months = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август',
    'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'] %}
{% block js %}
    $('.number[number = {{ n2m.get_number().get_id() }}] .meter[serial = {{ n2m.get_serial() }}][meter = {{ n2m.get_meter().get_id() }}] .meter-data-content').html(get_hidden_content())
{% endblock js %}
{% block html %}
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
{% endblock html %}