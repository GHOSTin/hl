{% set day = timeline %}
{% set month = timeline|date('n') %}
{% set months = {1 : 'Январь', 2 : 'Февраль', 3 : 'Март', 4: 'Апрель', 
    5 : 'Май', 6 : 'Июнь', 7 : 'Июль', 8 : 'Август', 9 : 'Сентябрь',
    10 : 'Октябрь', 11 : 'Ноябрь', 12 : 'Декабрь'} %}
<div class="timeline-month">
    {% if month in months|keys %}
        {{months[month]}}
    {% endif %}
    {{timeline|date('Y')}}
</div>
{% for i in range(1, timeline|date('t')) %}
    <div class="timeline-day" time="{{day}}">{{i}}</div>
    {% set day = day + 86400 %}
{% endfor %}