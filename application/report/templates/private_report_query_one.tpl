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
    {% for query in queries %}
        {% set work_time = 0 %}
    <tr>
        <td>{{ query.number }}</td>
        <td>{{ statuses[query.status] }}</td>
        <td>{{ warning_statuses[query.warning_status] }}</td>
        <td>{{ query.work_type_name }}</td>
        <td>{{ payment_statuses[query.payment_status] }}</td>
        <td>{{ query.department_name }}</td>
        <td>{{ query.street_name }}</td>
        <td>{{ query.house_number }}</td>
        <td>
            {% if query.initiator == 'number' %}
                {% for number_id in numbers.structure[query.id]['true'] %}
                    {% set number = numbers.numbers[number_id] %}
                    {{ number.fio }} (№{{ number.number }}), кв. {{ number.flat_number }}
                {% endfor %}
            {% endif %}
        </td>
        <td>{{ query.time_open|date("h.i d.m.Y") }}</td>
        <td>{% if query.status == 'close' or query.status == 'reclose' %}{{ query.time_close|date("h.i d.m.Y") }}{% endif %}</td>
        <td>{{ query.description }}</td>
        <td>{{ query.close_reason }}</td>
        <td>
            {% for wstrct in works.structure[query.id] %}
                {% set work = works.works[wstrct.work_id] %}
                {{ work.name }}
                {% set work_time = (work_time + (wstrct.time_close - wstrct.time_open)) // 60 %}
            {% endfor %}
        </td>
        <td>{{ work_time }}</td>
        <td>
            {% for class, user_ids in users.structure[query.id] %}
                <div></div>
                {% for user_id in user_ids %}
                    {% set user = users.users[user_id] %}
                    {{ user.lastname }} {{ user.firstname }} {{ user.middlename }} ({{ user_roles[class] }})
                {% endfor %}
            {% endfor %}
        </td>
        <td></td>
    </tr>
    {% endfor %}
</table>
{% endblock component %}