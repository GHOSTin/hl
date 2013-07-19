{% extends "print.tpl" %}
{% set queries = component.queries %}
{% set users = component.users %}

{% set statuses = {'open':'Открытая', 'working':'В работе',  'close': 'Закрытая', 'reopen':'Переоткрытая'} %}
{% set payment_statuses = {'paid':'Оплачиваемая', 'unpaid':'Неоплачиваемая', 'recalculation': 'Перерасчет'} %}
{% set warning_statuses = {'hight':'аварийная', 'normal':'на участок', 'planned': 'плановая'} %}
{% set user_roles = {'creator':'Диспетчер', 'manager':'Ответственный', 'performer': 'Исполнитель', 'observer': 'Наблюдатель'} %}

{% block component %}
<table>
    <tr>
        <td style="border:1px solid black">№</td>
        <td style="border:1px solid black">Статус</td>
        <td style="border:1px solid black">Тип</td>
        <td style="border:1px solid black">Работы</td>
        <td style="border:1px solid black">Оплата</td>
        <td style="border:1px solid black">Участок</td>
        <td style="border:1px solid black">Улица</td>
        <td style="border:1px solid black">Дом</td>
        <td style="border:1px solid black">Лицевые счета</td>
        <td style="border:1px solid black">Время открытия задачи</td>
        <td style="border:1px solid black">Время закрытия задачи</td>
        <td style="border:1px solid black">Описание открытия</td>
        <td style="border:1px solid black">Описание закрытия</td>
        <td style="border:1px solid black">Работы</td>
        <td style="border:1px solid black">Суммарное время работ</td>
        <td style="border:1px solid black">Сотрудники</td>
        <td style="border:1px solid black">Материалы</td>
    </tr>
    {% for query in queries %}
    <tr>
        <td style="border:1px solid black">{{ query.number }}</td>
        <td style="border:1px solid black">{{ statuses[query.status] }}</td>
        <td style="border:1px solid black">{{ warning_statuses[query.warning_status] }}</td>
        <td style="border:1px solid black">{{ query.work_type_name }}</td>
        <td style="border:1px solid black">{{ payment_statuses[query.payment_status] }}</td>
        <td style="border:1px solid black">ID:{{ query.department_id }}</td>
        <td style="border:1px solid black">{{ query.street_name }}</td>
        <td style="border:1px solid black">{{ query.house_number }}</td>
        <td style="border:1px solid black"></td>
        <td style="border:1px solid black">{{ query.time_open|date("h.i d.m.Y") }}</td>
        <td style="border:1px solid black">{% if query.status == 'close' or query.status == 'reclose' %}{{ query.time_close|date("h.i d.m.Y") }}{% endif %}</td>
        <td style="border:1px solid black">{{ query.description }}</td>
        <td style="border:1px solid black">{{ query.close_reason }}</td>
        <td style="border:1px solid black"></td>
        <td style="border:1px solid black"></td>
        <td style="border:1px solid black">
            {% for class, user_ids in users.structure[query.id] %}
                <div></div>
                {% for user_id in user_ids %}
                    {% set user = users.users[user_id] %}
                    {{ user.lastname }} {{ user.firstname }} {{ user.middlename }} ({{ user_roles[class] }})
                {% endfor %}
            {% endfor %}
        </td>
        <td style="border:1px solid black"></td>
    </tr>
    {% endfor %}
</table>
{% endblock component %}