{% for group in workgroups %}
<li class="workgroup list-group-item {% if loop.first %}fist-item{% endif %}" workgroup_id="{{ group.get_id() }}">
  <a href="#" class="client-link workgroup-title">{{ group.get_name() }}</a>
</li>
{% endfor %}