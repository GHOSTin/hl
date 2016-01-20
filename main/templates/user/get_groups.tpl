<div>
    <a class="btn btn-default get_dialog_create_group">Создать группу</a>
</div>
<ul class="list-unstyled groups">
{% for group in groups %}
  <li class="group" group="{{ group.get_id() }}">
      <div class="group-name get_group_content">{{ group.get_name() }}</div>
  </li>
{% endfor %}
</ul>