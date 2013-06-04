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
    <table class="table table-condensed span12">
        <caption>
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
        </caption>
    {% for i in 0..11 %}
        {% set data = component.meter_data[date|date("U")] %}
        <tr class="month" time="{{ date|date("U") }}">
            <td>{{ months[i] }}</td>
            <td><input type="text" value="{{ data[0] }}" class="tarif1 input-small" maxlength="{{ meter.capacity }}"></td>
            {% if meter.rates == 2 %}
            <td><input type="text" value="{{ data[1] }}" class="tarif2 input-small" maxlength="{{ meter.capacity }}"></td>
            {% endif %}
            {% if meter.rates == 3 %}
            <td><input type="text" value="{{ data[1] }}" class="tarif2 input-small" maxlength="{{ meter.capacity }}"></td>
            <td><input type="text" value="{{ data[2] }}" class="tarif3 input-small" maxlength="{{ meter.capacity }}"></td>
            {% endif %}
            <td><a class="btn get_dialog_edit_meter_data">изменить</a></td>
        </tr>
        {% set date = date|date_modify("+1 month") %}
    {% endfor %}
    </table>
</div>
{% endblock html %}