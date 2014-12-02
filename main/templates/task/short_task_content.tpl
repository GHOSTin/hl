{% if task.get_status() == 'open' %}
  {% set days = ((task.get_time_target() - "now"|date("U"))/86400)|round(0, 'floor')|abs %}
  {% if days == 0 %}
    {% set hours = ((task.get_time_target() - "now"|date("U"))/3600)|round(0, 'floor')|abs %}
  {% endif %}
  {%  if task.get_time_target() > "now"|date("U")%}
    {% set number = "+" %}
    {% set label = 'success' %}
  {% else %}
    {% set number = "-" %}
    {% set label = 'danger' %}
  {% endif %}
  {% if days != 0 %}
    {% set icon = number ~ days ~ "<small>дней</small>" %}
  {% else %}
    {% set icon = number ~ hours ~ "<small>часов</small>" %}
  {% endif %}
{% else %}
  {% set label = 'success' %}
  {% set icon = '<i class="glyphicon glyphicon-lock"></i>' %}
{% endif %}
<a href="#{{ task.get_id() }}/" class="list-group-item" data-ajax="true">
  <div class="media">
    <div class="pull-left">
          <span class="label label-{{ label }} media-object task-media">
            {{ icon|raw }}
          </span>
      <span class="glyphicon glyphicon-comment task-comment">{{ task.get_comments()|length }}</span>
    </div>
    <div class="media-body">
      <h5 class="list-group-item-heading media-heading"><strong>{{ task.get_title()|nl2br }}</strong></h5>
      {% set creator = task.get_creator().get_user() %}
      <p class="list-group-item-text">
        <i class="glyphicon glyphicon-user" style="font-size: 20px;"></i>
        {{ creator.get_lastname()}} {{ creator.get_firstname()|first|upper }}.{{ creator.get_middlename()|first|upper }}.</p>
    </div>
  </div>
</a>
