{% extends "print.tpl" %}
{% set number = response.number %}
{% set meter = response.meter %}
{% set services = {'cold_water':'холодного водоснабжения',
    'hot_water':'горячего водоснабжения', 'electrical':'электроэнергии'} %}
{% set rates = ['Однотарифный', 'Двухтарифный', 'Трехтарифный'] %}
{% set places = {'kitchen':'на кухне', 'toilet':'в туалете', 'bathroom':'в ванной'} %}
{% block component %}


<h5>{#{ number.number.get_flat().get_house().get_house().get_name() }#}, дом №{{ number.get_flat().get_house().get_number() }}, кв. №{{ number.get_flat().get_number() }}, {{ number.get_fio() }} (л/с №{{ number.get_number() }})</h5>
{{ rates[meter.get_rates() - 1] }} счетчик {{ services[meter.get_service()] }} {{ meter.get_name() }} ( Заводской номер №{{ meter.get_serial() }}, разрядность {{ meter.get_capacity() }}).
{% if meter.get_service() == 'cold_water' or meter.get_service() == 'hot_water' %}
    Установлен {{ places[meter.get_place()] }}.
{% endif %}
<p>
    Дата производства: {{ meter.get_date_release()|date('d.m.Y') }} Дата установки: {{ meter.get_date_install()|date('d.m.Y') }}
    Дата последней поверки: {{ meter.get_date_checking()|date('d.m.Y') }}<br>
    Период поверки: {{ meter.get_period() // 12 }} г {{ meter.get_period() % 12 }} мес.
    Дата следующей поверки: {{ meter.get_date_next_checking()|date('d.m.Y') }}
</p>
{% if response.n2m_data|length >0 %}
    <table>
        <tr>
            <td>Время</td>
            {% if n2m.get_rates() == 1 %}
                <td>1 тариф</td>
            {% endif %}
            {% if n2m.get_rates() == 2 %}
                <td>1 тариф</td>
                <td>2 тариф</td>
            {% endif %}
            {% if n2m.get_rates() == 3 %}
                <td>1 тариф</td>
                <td>2 тариф</td>
                <td>3 тариф</td>
            {% endif %}
        </tr>
    {% for data in response.n2m_data %}
        <tr>
            <td>{{data.time|date('m.Y')}}</td>
            {% if n2m.get_rates() == 1 %}
                <td>{{ data.value[0] }}</td>
            {% endif %}
            {% if n2m.get_rates() == 2 %}
                <td>{{ data.value[0] }}</td>
                <td>{{ data.value[1] }}</td>
            {% endif %}
            {% if n2m.get_rates() == 3 %}
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