{% set current_day_time = component.timeline %}
{% set m = current_day_time|date("n") %}
{% set f = current_day_time|date("F") %}
{% set y = current_day_time|date("Y") %}
{% set day = now|date_modify(['12:00 01 ', f, ' ', y]|join) %}
{% set months = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль',
		'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'] %}
{% set wdays = ['вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб'] %}
<div class="timeline-month" time="{{day|date('U')}}">
    {% if month in months|keys %}
        {{ months[m-1] }}
    {% endif %}
    {{ y }}
</div>
<i class="icon-chevron-left get_timeline" act="previous"></i>
{% for i in range(1, current_day_time|date('t')) %}
    <div class="timeline-day
        {% if day == current_day_time %}
            timeline-day-current
        {% endif %}
        " time="{{day|date('U')}}" title="{{day|date('d.m.Y')}}">
    	<div>{{ i }}</div>
    	<div class="timeline-day-wday">
    		{% set w = day|date('w') %}
    		{% if w in wdays|keys %}
    			{{ wdays[w] }}
    		{% endif %}
    	</div>
    </div>
    {% set day = day|date_modify('+1 day') %}
{% endfor %}
<i class="icon-chevron-right get_timeline" act="next"></i>