{% for event in events %}
<li class="well">
  <p>{{ event.get_time()|date("d.m.Y") }} {{ event.get_name() }}</p>
  <p>{{ event.get_description() }}</p>
</li>
{% endfor %}