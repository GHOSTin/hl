{% extends "print.tpl" %}
{% set meter = component.meters[0] %}
{% set number = component.numbers[0] %}
{% set services = {'cold_water':'холодного водоснабжения',
    'hot_water':'горячего водоснабжения', 'electrical':'электроэнергии'} %}
{% set rates = ['Однотарифный', 'Двухтарифный', 'Трехтарифный'] %}
{% set places = {'kitchen':'на кухне', 'toilet':'в туалете', 'bathroom':'в ванной'} %}
{% block component %}


<h5>{{ number.street_name }}, дом №{{ number.house_number }}, кв. №{{ number.flat_number }}, {{ number.fio }} (л/с №{{ number.number }})</h5>
{{ rates[meter.rates - 1] }} счетчик {{ services[meter.service] }} {{ meter.name }} ( Заводской номер №{{ meter.serial }}, разрядность {{ meter.capacity }}).
{% if meter.service == 'cold_water' or meter.service == 'hot_water' %}
    Установлен {{ places[meter.place] }}.
{% endif %}
<p>
    Дата производства: {{ meter.date_release|date('d.m.Y') }} Дата установки: {{ meter.date_install|date('d.m.Y') }}
    Дата последней поверки: {{ meter.date_checking|date('d.m.Y') }}<br>
    Период поверки: {{ meter.period // 12 }} г {{ meter.period % 12 }} мес.
    Дата следующей поверки: {{ meter.date_next_checking|date('d.m.Y') }}
</p>
{% if component.meter_data|length >0 %}
    <table>
        <tr>
            <td>Время</td>
            {% if meter.rates == 1 %}
                <td>1 тариф</td>
            {% endif %}
            {% if meter.rates == 2 %}
                <td>1 тариф</td>
                <td>2 тариф</td>
            {% endif %}
            {% if meter.rates == 3 %}
                <td>1 тариф</td>
                <td>2 тариф</td>
                <td>3 тариф</td>
            {% endif %}
        </tr>
    {% for data in component.meter_data %} 
        <tr>
            <td>{{data.time|date('m.Y')}}</td>
            {% if meter.rates == 1 %}
                <td>{{ data.value[0] }}</td>
            {% endif %}
            {% if meter.rates == 2 %}
                <td>{{ data.value[0] }}</td>
                <td>{{ data.value[1] }}</td>
            {% endif %}
            {% if meter.rates == 3 %}
                <td>{{ data.value[0] }}</td>
                <td>{{ data.value[1] }}</td>
                <td>{{ data.value[2] }}</td>
            {% endif %}
        </tr>
    {% endfor %}
    </table>
{% else %}
    Нет показаний
{% endif %}
{% endblock component %}