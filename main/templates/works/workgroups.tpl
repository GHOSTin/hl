{% for group in workgroups %}
<li class="workgroup" workgroup_id="{{ group.get_id() }}">
  <div class="workgroup-title">{{ group.get_name() }}</div>
</li>
{% endfor %}