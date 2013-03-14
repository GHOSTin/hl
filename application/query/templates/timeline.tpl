{% set day = component.timeline %}
{% set month = component.timeline|date('n') %}
{% set months = {1 : 'января', 2 : 'февраля', 3 : 'марта', 4: 'апреля', 
    5 : 'мая', 6 : 'июня', 7 : 'июль', 8 : 'августа', 9 : 'сентября',
    10 : 'октября', 11 : 'ноября', 12 : 'декабря'} %}
<div class="timeline-month" time="{{component.timeline}}">
    {% if month in months|keys %}
        {{months[month]}}
    {% endif %}
    {{component.timeline|date('Y')}}
</div>
<i class="icon-chevron-left get_timeline" act="previous"></i>
{% for i in range(1, component.timeline|date('t')) %}
    <div class="timeline-day" time="{{day}}">{{i}}</div>
    {% set day = day + 86400 %}
{% endfor %}
<i class="icon-chevron-right get_timeline" act="next"></i>