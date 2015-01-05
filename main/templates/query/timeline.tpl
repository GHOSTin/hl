{% set m = timeline|date("n") %}
{% set y = timeline|date("Y") %}
{% set now = "now"|date_modify("noon") %}
{% set day = timeline|date_modify("noon first day of this month") %}
{% set wdays = ['вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб'] %}
{% set months = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'] %}
<div class="timeline-month" time="{{ timeline|date('U') }}">{{ months[m-1] }} {{ y }}</div>
<div class="btn-group" data-toggle="buttons">
  <a class="timeline-control btn btn-primary get_timeline" act="previous">
    <i class="glyphicon glyphicon-chevron-left"></i>
  </a>
  {% for i in range(1, timeline|date('t')) %}
    <a class="timeline-day btn btn-primary
      {% if day|date('d.m.Y') == timeline|date('d.m.Y') %} active{% endif %}
      {% if day|date('d.m.Y') == now|date('d.m.Y') %} btn-info{% endif %}
      " time="{{day|date('U')}}" title="{{ day|date('d.m.Y') }}">
      <input type="radio">
      <div>{{ i }}</div>
      <div class="timeline-day-wday">
        {% set w = day|date('w') %}
        {% if w in wdays|keys %}
          {% if w in [0, 6] %}<b>{% endif %}
          {{ wdays[w] }}
          {% if w in [0, 6] %}</b>{% endif %}
        {% endif %}
      </div>
    </a>
    {% set day = day|date_modify('noon next day') %}
  {% endfor %}
  <a class="timeline-control btn btn-primary get_timeline" act="next">
    <i class="glyphicon glyphicon-chevron-right"></i>
  </a>
</div>