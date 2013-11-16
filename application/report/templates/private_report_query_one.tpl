{% extends "print.tpl" %}
{% set queries = component.queries %}
{% set users = component.users %}
{% set works = component.works %}
{% set numbers = component.numbers %}

{% set statuses = {'open':'Открытая', 'working':'В работе',  'close': 'Закрытая', 'reopen':'Переоткрытая'} %}
{% set payment_statuses = {'paid':'Оплачиваемая', 'unpaid':'Неоплачиваемая', 'recalculation': 'Перерасчет'} %}
{% set warning_statuses = {'hight':'аварийная', 'normal':'на участок', 'planned': 'плановая'} %}
{% set user_roles = {'creator':'Диспетчер', 'manager':'Ответственный', 'performer': 'Исполнитель', 'observer': 'Наблюдатель'} %}
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
    {% for query in queries.get_queries() %}
        {% set work_time = 0 %}
    <tr>
        <td>{{ query.get_number() }}</td>
        <td>{{ statuses[query.get_status()] }}</td>
        <td>{{ warning_statuses[query.get_warning_status()] }}</td>
        <td>{{ query.get_work_type().get_name() }}</td>
        <td>{{ payment_statuses[query.get_payment_status()] }}</td>
        <td>{{ query.get_department().get_name() }}</td>
        <td>{{ query.get_street().get_name() }}</td>
        <td>{{ query.get_house().get_number() }}</td>
        <td>
            {% if query.get_initiator() == 'number' %}
                {% for number in query.get_numbers() %}
                    {{ number.fio }} (№{{ number.number }}), кв. {{ number.flat_number }}
                {% endfor %}
            {% endif %}
        </td>
        <td>{{ query.get_time_open()|date("h.i d.m.Y") }}</td>
        <td>{% if query.get_status() == 'close' or query.get_status() == 'reclose' %}{{ query.get_time_close()|date("h.i d.m.Y") }}{% endif %}</td>
        <td>{{ query.get_description() }}</td>
        <td>{{ query.get_close_reason() }}</td>
        <td>
            {#
            {% for wstrct in works.structure[query.get_id()] %}
                {% set work = works.works[wstrct.work_id] %}
                {{ work.name }}
                {% set work_time = (work_time + (wstrct.time_close - wstrct.time_open)) // 60 %}
            {% endfor %}
            #}
        </td>
        <td>{{ work_time }}</td>
        <td>
            {% set creator = query.get_creator() %}
            <div>{{ creator.get_lastname() }} {{ creator.get_firstname() }} {{ creator.get_middlename() }} (диспетчер)</div>
            {% for user in query.get_managers() %}
                <div>{{ user.get_lastname() }} {{ user.get_firstname() }} {{ user.get_middlename() }} (менеджер)</div>
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