{% for work in works %}
<li class="work list-group-item {% if loop.first %}fist-item{% endif %}" work_id="{{ work.get_id() }}">
  <a href="#" class="client-link work-title">{{ work.get_name() }}</a>
</li>
{% endfor %}