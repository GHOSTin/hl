{% for event in events %}
<li class="event" event_id="{{ event.get_id() }}">
  <div class="event-title">{{ event.get_name() }}</div>
</li>
{% endfor %}