{% extends "print.tpl" %}
{% set n2m = component.n2m %}
{% set number = n2m.get_number() %}
{% set services = {'cold_water':'холодного водоснабжения',
    'hot_water':'горячего водоснабжения', 'electrical':'электроэнергии'} %}
{% set rates = ['Однотарифный', 'Двухтарифный', 'Трехтарифный'] %}
{% set places = {'kitchen':'на кухне', 'toilet':'в туалете', 'bathroom':'в ванной'} %}
{% block component %}


<h5>{#{ number.get_street_name() }#}, дом №{#{ number.house_number }#}, кв. №{{ number.get_flat_number() }}, {{ number.get_fio() }} (л/с №{{ number.get_number() }})</h5>
{{ rates[n2m.get_meter().get_rates() - 1] }} счетчик {{ services[n2m.get_service()] }} {{ n2m.get_meter().get_name() }} ( Заводской номер №{{ n2m.get_serial() }}, разрядность {{ n2m.get_meter().get_capacity() }}).
{% if n2m.get_service() == 'cold_water' or n2m.get_service() == 'hot_water' %}
    Установлен {{ places[n2m.get_place()] }}.
{% endif %}
<p>
    Дата производства: {{ n2m.get_date_release()|date('d.m.Y') }} Дата установки: {{ n2m.get_date_install()|date('d.m.Y') }}
    Дата последней поверки: {{ n2m.get_date_checking()|date('d.m.Y') }}<br>
    Период поверки: {{ n2m.get_period() // 12 }} г {{ n2m.get_period() % 12 }} мес.
    Дата следующей поверки: {{ n2m.get_date_next_checking()|date('d.m.Y') }}
</p>
{% if component.n2m_data|length >0 %}
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
    {% for data in component.n2m_data %} 
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