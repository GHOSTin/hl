{% for event in events %}
<li class="event list-group-item {% if loop.first %}fist-item{% endif %}" event_id="{{ event.get_id() }}">
  <a href="#" class="client-link event-title">{{ event.get_name() }}</a>
</li>
{% endfor %}