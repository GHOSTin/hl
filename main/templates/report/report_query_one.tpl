{% extends "print.tpl" %}

{% set statuses = {'open':'Открытая', 'working':'В работе',  'close': 'Закрытая', 'reopen':'Переоткрытая'} %}
{% set payment_statuses = {'paid':'Оплачиваемая', 'unpaid':'Неоплачиваемая', 'recalculation': 'Перерасчет'} %}

{% block css %}
<style>
    table tr td{
        border:1px solid black;
        vertical-align: top;
        white-space: nowrap;
    }
</style>
{% endblock css %}

{% block component %}
<table>
    <tr>
        <td>№</td>
        <td>Статус</td>
        <td>Тип</td>
        <td>Работы</td>
        <td>Оплата</td>
        <td>Участок</td>
        <td>Улица</td>
        <td>Дом</td>
        <td>Кв.</td>
        <td>Лицевые счета</td>
        <td>Время открытия</td>
        <td>Время закрытия</td>
        <td>Описание открытия</td>
        <td>Описание закрытия</td>
        <td>Работы</td>
        <td>Суммарное время работ</td>
        <td>Сотрудники</td>
        <td>Материалы</td>
    </tr>
    {% for query in queries %}
        {% set work_time = 0 %}
    <tr>
        <td>{{ query.get_number() }}</td>
        <td>{{ statuses[query.get_status()] }}</td>
        <td>{{ query.get_query_type().get_name() }}</td>
        <td>{{ query.get_work_type().get_name() }}</td>
        <td>{{ payment_statuses[query.get_payment_status()] }}</td>
        <td>{{ query.get_department().get_name() }}</td>
        <td>{{ query.get_house().get_street().get_name() }}</td>
        <td>{{ query.get_house().get_number() }}</td>
        <td>
          {% if query.get_initiator() == 'number' %}
              {% for number in query.get_numbers() %}
                {{ number.get_flat().get_number() }}
              {% endfor %}
          {% endif %}
        </td>
        <td>
            {% if query.get_initiator() == 'number' %}
                {% for number in query.get_numbers() %}
                  №{{ number.get_number() }} ({{ number.get_fio() }})
                {% endfor %}
            {% endif %}
        </td>
        <td>{{ query.get_time_open()|date("h.i d.m.Y") }}</td>
        <td>{% if query.get_status() == 'close' or query.get_status() == 'reclose' %}{{ query.get_time_close()|date("h.i d.m.Y") }}{% endif %}</td>
        <td>{{ query.get_description() }}</td>
        <td>{{ query.get_close_reason() }}</td>
        <td>
            {% for work in query.get_works() %}
                {{ work.get_name() }}
                {% set work_time = (work_time + (work.get_time_close() - work.get_time_open())) // 60 %}
            {% endfor %}
        </td>
        <td>{{ work_time }}</td>
        <td>
            {% set creator = query.get_creator() %}
            <div>{{ creator.get_lastname() }} {{ creator.get_firstname() }} {{ creator.get_middlename() }} (диспетчер)</div>
            {% for user in query.get_managers() %}
                <div>{{ user.get_lastname() }} {{ user.get_firstname() }} {{ user.get_middlename() }} (ответственный)</div>
            {% endfor %}
            {% for user in query.get_performers() %}
                <div>{{ user.get_lastname() }} {{ user.get_firstname() }} {{ user.get_middlename() }} (исполнитель)</div>
            {% endfor %}
        </td>
        <td></td>
    </tr>
    {% endfor %}
</table>
{% endblock component %}