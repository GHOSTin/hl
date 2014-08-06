{% set current_day_time = response.timeline %}
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
<div class="btn-group" data-toggle="buttons">
    <a class="timeline-control btn btn-primary get_timeline" act="previous">
        <i class="glyphicon glyphicon-chevron-left"></i>
    </a>
    {% for i in range(1, current_day_time|date('t')) %}
        <a class="timeline-day btn btn-primary
            {% if day|date('U') == current_day_time %}
                active
            {% endif %}
            {% if day|date('U') == response.now %}
                btn-info
            {% endif %}
            " time="{{day|date('U')}}" title="{{day|date('d.m.Y')}}">
            <input type="radio">
            <div>{{ i }}</div>
            <div class="timeline-day-wday">
                {% set w = day|date('w') %}
                {% if w in wdays|keys %}
                    {% if w in [0, 6] %}
                        <b>
                    {% endif %}
                    {{ wdays[w] }}
                    {% if w in [0, 6] %}
                        </b>
                    {% endif %}
                {% endif %}
            </div>
        </a>
        {% set day = day|date_modify('+1 day') %}
    {% endfor %}
    <a class="timeline-control btn btn-primary get_timeline" act="next">
        <i class="glyphicon glyphicon-chevron-right"></i>
    </a>
</div>